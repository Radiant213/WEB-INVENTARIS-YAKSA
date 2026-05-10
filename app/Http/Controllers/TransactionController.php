<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Item;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['item.category', 'user']);

        // Filter gudang via item
        if ($request->filled('gudang') && $request->gudang !== 'universal') {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('gudang', $request->gudang);
            });
        }

        // Filter tipe transaksi
        if ($request->filled('tipe') && $request->tipe !== 'all') {
            $query->where('tipe_transaksi', $request->tipe);
        }

        // Filter kategori barang
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->where('tanggal_transaksi', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->where('tanggal_transaksi', '<=', $request->end_date);
        }

        // Gunakan latest() by id agar yang baru diinput (walau tanggal sama) tetap di atas
        $transactions = $query->orderByDesc('tanggal_transaksi')->latest('id')->paginate(15)->withQueryString();
        
        $itemsQuery = Item::orderBy('nama_perangkat');
        $categoriesQuery = \App\Models\Category::orderBy('name');
        
        $activeGudang = $request->get('gudang', 'universal');
        
        if ($activeGudang !== 'universal') {
            $itemsQuery->where('gudang', $activeGudang);
            $categoriesQuery->where('gudang', $activeGudang);
        }
        
        $items = $itemsQuery->get();
        $categories = $categoriesQuery->get();
        
        return view('transactions.index', compact('transactions', 'items', 'categories', 'activeGudang'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'tipe_transaksi' => 'required|in:in,out',
            'pengirim' => 'nullable|string|max:255',
            'penerima' => 'nullable|string|max:255',
            'tanggal_transaksi' => 'required|date',
            'bukti_foto' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:5120',
        ]);

        // Validasi In/Out
        $lastTransaction = Transaction::where('item_id', $data['item_id'])
                                        ->orderByDesc('tanggal_transaksi')
                                        ->latest('id')
                                        ->first();
                                        
        if ($lastTransaction) {
            if ($data['tipe_transaksi'] === 'in' && $lastTransaction->tipe_transaksi === 'in') {
                return back()->with('error', 'Barang ini sudah berstatus IN (Masuk). Harus diinput OUT (Keluar) terlebih dahulu!');
            }
            if ($data['tipe_transaksi'] === 'out' && $lastTransaction->tipe_transaksi === 'out') {
                return back()->with('error', 'Barang ini sudah berstatus OUT (Keluar). Harus diinput IN (Masuk) terlebih dahulu!');
            }
        } else {
            // Jika belum ada transaksi sama sekali, cek dari status Item saat ini (Opsional tapi lebih aman)
            $item = Item::find($data['item_id']);
            if ($data['tipe_transaksi'] === 'in' && $item->status === 'Ready') {
                return back()->with('error', 'Barang ini berstatus Ready (IN). Harus diinput OUT terlebih dahulu!');
            }
            if ($data['tipe_transaksi'] === 'out' && in_array($item->status, ['Barang Keluar', 'Barang RMA', 'Barang Rusak', 'Delivered'])) {
                return back()->with('error', 'Barang ini sudah keluar/tidak ready. Harus diinput IN terlebih dahulu!');
            }
        }

        $userRole = Auth::user()->role ?? 'user';
        $status = $userRole === 'user' ? 'pending' : 'approved';

        $buktiFotoPath = null;
        if ($request->hasFile('bukti_foto')) {
            $buktiFotoPath = $request->file('bukti_foto')->store('bukti_transaksi', 'public');
        }

        $transaction = Transaction::create([
            'item_id' => $data['item_id'],
            'user_id' => Auth::id(),
            'tipe_transaksi' => $data['tipe_transaksi'],
            'pengirim' => $data['pengirim'] ?? null,
            'penerima' => $data['penerima'] ?? null,
            'tanggal_transaksi' => $data['tanggal_transaksi'],
            'status' => $status,
            'bukti_foto' => $buktiFotoPath,
        ]);

        $item = Item::find($data['item_id']);
        $type = $data['tipe_transaksi'] === 'in' ? 'Masuk' : 'Keluar';

        if ($status === 'approved') {
            // Update item status directly for admin
            if ($data['tipe_transaksi'] === 'in') {
                $item->status = 'Ready';
            } else {
                $item->status = 'Barang Keluar';
            }
            $item->save();
        }
        
        if ($status === 'pending') {
            Notification::create([
                'type' => 'approval_request',
                'title' => "Request Approval {$type}",
                'message' => Auth::user()->name . " merequest transaksi {$type} ({$item->nama_perangkat})",
                'link' => '/approvals',
            ]);
            return back()->with('success', 'Request transaksi berhasil dicatat dan menunggu approval admin!');
        } else {
            Notification::create([
                'type' => 'transaction_' . $data['tipe_transaksi'],
                'title' => "Barang {$type}",
                'message' => "{$item->nama_perangkat} - {$type} oleh " . Auth::user()->name,
                'link' => route('transactions.index'),
            ]);
            return back()->with('success', 'Transaksi berhasil dicatat!');
        }
    }
    
    public function edit(Transaction $transaction)
    {
        $items = Item::where('gudang', $transaction->item->gudang)->get();
        return view('transactions.edit', compact('transaction', 'items'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'tipe_transaksi' => 'required|in:in,out',
            'pengirim' => 'nullable|string|max:255',
            'penerima' => 'nullable|string|max:255',
            'tanggal_transaksi' => 'required|date',
            'bukti_foto' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:5120',
        ]);

        $oldTipe = $transaction->tipe_transaksi;

        if ($request->hasFile('bukti_foto')) {
            if ($transaction->bukti_foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($transaction->bukti_foto);
            }
            $buktiFotoPath = $request->file('bukti_foto')->store('bukti_transaksi', 'public');
            $data['bukti_foto'] = $buktiFotoPath;
        }

        $transaction->update($data);

        // Jika tipe transaksi diubah, sinkronkan status item
        if ($oldTipe !== $data['tipe_transaksi'] && $transaction->status === 'approved') {
            $item = Item::find($data['item_id']);
            if ($data['tipe_transaksi'] === 'in') {
                $item->status = 'Ready';
            } else {
                $item->status = 'Barang Keluar';
            }
            $item->save();
        }

        return redirect()->route('transactions.index', ['gudang' => $transaction->item->gudang])
                         ->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->bukti_foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($transaction->bukti_foto);
        }
        $transaction->delete();
        // Option: we could also dispatch notification
        return back()->with('success', 'Log transaksi berhasil dihapus!');
    }
}
