<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Notification;
use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['transactions.user', 'category', 'user']);

        // Hanya admin yang bisa lihat barang pending
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            $query->where('status_approval', 'approved');
        }

        // Filter gudang
        if ($request->filled('gudang') && $request->gudang !== 'universal') {
            $query->where('gudang', $request->gudang);
        }

        // Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter lokasi
        if ($request->filled('lokasi') && $request->lokasi !== 'all') {
            $query->where('lokasi_device', $request->lokasi);
        }

        // Filter berdasarkan kategori (tab system)
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        // Filter search global (q)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($qBuilder) use ($q) {
                $qBuilder->where('nama_perangkat', 'like', "%{$q}%")
                        ->orWhere('serial_number', 'like', "%{$q}%")
                        ->orWhere('status', 'like', "%{$q}%")
                        ->orWhere('lokasi_device', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Filter rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date . ' 00:00:00');
        } elseif ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        $items = $query->latest()->paginate(15)->withQueryString();

        // Data untuk filter dropdown dan Tabs
        $categoriesQuery = \App\Models\Category::orderBy('name');
        if ($request->filled('gudang') && $request->gudang !== 'universal') {
            $categoriesQuery->where('gudang', $request->gudang);
        }
        $categories = $categoriesQuery->get();

        $statuses = Item::select('status')->distinct()->whereNotNull('status')->pluck('status');
        $lokasis = Item::select('lokasi_device')->distinct()->whereNotNull('lokasi_device')->pluck('lokasi_device');

        $activeGudang = $request->get('gudang', 'universal');

        return view('items.index', compact('items', 'statuses', 'lokasis', 'categories', 'activeGudang'));
    }

    // API search realtime
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $items = Item::with('category')
            ->where('nama_perangkat', 'like', "%{$q}%")
            ->orWhere('serial_number', 'like', "%{$q}%")
            ->orWhere('status', 'like', "%{$q}%")
            ->orWhere('lokasi_device', 'like', "%{$q}%")
            ->take(8)
            ->get(['id', 'nama_perangkat', 'serial_number', 'status', 'lokasi_device', 'category_id']);

        return response()->json($items);
    }

    public function create(Request $request)
    {
        $activeGudang = $request->get('gudang', 'jakarta');
        $categories = \App\Models\Category::where('gudang', $activeGudang)->orderBy('name')->get();
        return view('items.create', compact('categories', 'activeGudang'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_perangkat' => 'required|string|max:255',
            'gudang' => 'required|in:jakarta,bali,sfp',
            'serial_number' => 'required|string|unique:items,serial_number|max:255',
            'status' => 'required|string',
            'status_barang' => 'nullable|string',
            'os_version' => 'nullable|string',
            'lokasi_device' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();
        $isAdmin = $user && $user->isAdmin();
        $data['user_id'] = $user->id;
        $data['status_approval'] = $isAdmin ? 'approved' : 'pending';

        $item = Item::create($data);

        if ($isAdmin) {
            Notification::create([
                'type' => 'item_created',
                'title' => 'Barang Baru Ditambahkan',
                'message' => "{$item->nama_perangkat} ({$item->serial_number}) telah ditambahkan.",
                'link' => route('items.index'),
            ]);
            return redirect()->route('items.index', ['gudang' => $item->gudang])->with('success', 'Barang berhasil ditambahkan!');
        } else {
            Notification::create([
                'type' => 'approval_request',
                'title' => 'Request Penambahan Barang Baru',
                'message' => $user->name . " merequest penambahan barang: {$item->nama_perangkat} ({$item->serial_number})",
                'link' => '/approvals',
            ]);
            return redirect()->route('items.index', ['gudang' => $item->gudang])->with('success', 'Request penambahan barang berhasil dikirim dan menunggu approval admin!');
        }
    }

    // Edit form
    public function edit(Item $item)
    {
        $categories = \App\Models\Category::where('gudang', $item->gudang)->orderBy('name')->get();
        return view('items.edit', compact('item', 'categories'));
    }

    // Update
    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'nama_perangkat' => 'required|string|max:255',
            'gudang' => 'required|in:jakarta,bali,sfp',
            'serial_number' => 'required|string|max:255|unique:items,serial_number,' . $item->id,
            'status' => 'required|string',
            'status_barang' => 'nullable|string',
            'os_version' => 'nullable|string',
            'lokasi_device' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $item->update($data);

        return redirect()->route('items.index', ['gudang' => $item->gudang])->with('success', 'Barang berhasil diupdate!');
    }

    public function destroy(Item $item)
    {
        // Pilihan: Bisa hapus permanen atau bikin trigger log
        // Kita juga bisa hapus transaksi terkait agar gak error foreign key
        $buktiFiles = $item->transactions()->whereNotNull('bukti_foto')->pluck('bukti_foto')->toArray();
        if (!empty($buktiFiles)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($buktiFiles);
        }

        $item->transactions()->delete();
        
        $item->delete();

        Notification::create([
            'type' => 'item_deleted',
            'title' => 'Barang Dihapus',
            'message' => "{$item->nama_perangkat} ({$item->serial_number}) telah dihapus secara permanen.",
            'link' => route('items.index'),
        ]);

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus!');
    }

    public function export()
    {
        return Excel::download(new ItemsExport, 'inventaris-yaksa-' . date('Y-m-d') . '.xlsx');
    }
}
