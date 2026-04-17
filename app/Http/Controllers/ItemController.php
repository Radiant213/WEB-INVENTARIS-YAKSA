<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Notification;
use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('transactions.user');

        // Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter lokasi
        if ($request->filled('lokasi') && $request->lokasi !== 'all') {
            $query->where('lokasi_device', $request->lokasi);
        }

        $items = $query->latest()->paginate(15)->withQueryString();

        // Data untuk filter dropdown
        $statuses = Item::select('status')->distinct()->whereNotNull('status')->pluck('status');
        $lokasis = Item::select('lokasi_device')->distinct()->whereNotNull('lokasi_device')->pluck('lokasi_device');

        return view('items.index', compact('items', 'statuses', 'lokasis'));
    }

    // API search realtime
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $items = Item::where('nama_perangkat', 'like', "%{$q}%")
            ->orWhere('serial_number', 'like', "%{$q}%")
            ->orWhere('status', 'like', "%{$q}%")
            ->orWhere('lokasi_device', 'like', "%{$q}%")
            ->take(8)
            ->get(['id', 'nama_perangkat', 'serial_number', 'status', 'lokasi_device']);

        return response()->json($items);
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_perangkat' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:items,serial_number|max:255',
            'status' => 'required|string',
            'status_barang' => 'nullable|string',
            'os_version' => 'nullable|string',
            'lokasi_device' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $item = Item::create($data);

        Notification::create([
            'type' => 'item_created',
            'title' => 'Barang Baru Ditambahkan',
            'message' => "{$item->nama_perangkat} ({$item->serial_number}) telah ditambahkan.",
            'link' => route('items.index'),
        ]);

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    // Edit form
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    // Update
    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'nama_perangkat' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:items,serial_number,' . $item->id,
            'status' => 'required|string',
            'status_barang' => 'nullable|string',
            'os_version' => 'nullable|string',
            'lokasi_device' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Barang berhasil diupdate!');
    }

    public function destroy(Item $item)
    {
        // Pilihan: Bisa hapus permanen atau bikin trigger log
        // Kita juga bisa hapus transaksi terkait agar gak error foreign key
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
