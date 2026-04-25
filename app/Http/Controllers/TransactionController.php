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

        $transactions = $query->latest('tanggal_transaksi')->paginate(15)->withQueryString();
        
        $itemsQuery = Item::orderBy('nama_perangkat');
        $categoriesQuery = \App\Models\Category::orderBy('name');
        
        if ($request->filled('gudang') && $request->gudang !== 'universal') {
            $itemsQuery->where('gudang', $request->gudang);
            $categoriesQuery->where('gudang', $request->gudang);
        }
        
        $items = $itemsQuery->get();
        $categories = $categoriesQuery->get();
        
        $activeGudang = $request->get('gudang', 'universal');

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
        ]);

        $transaction = Transaction::create([
            'item_id' => $data['item_id'],
            'user_id' => Auth::id(),
            'tipe_transaksi' => $data['tipe_transaksi'],
            'pengirim' => $data['pengirim'] ?? null,
            'penerima' => $data['penerima'] ?? null,
            'tanggal_transaksi' => $data['tanggal_transaksi'],
        ]);

        $item = Item::find($data['item_id']);
        $type = $data['tipe_transaksi'] === 'in' ? 'Masuk' : 'Keluar';
        Notification::create([
            'type' => 'transaction_' . $data['tipe_transaksi'],
            'title' => "Barang {$type}",
            'message' => "{$item->nama_perangkat} - {$type} oleh " . Auth::user()->name,
            'link' => route('transactions.index'),
        ]);

        return back()->with('success', 'Transaksi berhasil dicatat!');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        // Option: we could also dispatch notification
        return back()->with('success', 'Log transaksi berhasil dihapus!');
    }
}
