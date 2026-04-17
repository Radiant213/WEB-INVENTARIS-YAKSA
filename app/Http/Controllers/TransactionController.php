<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Item;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['item', 'user'])
            ->latest('tanggal_transaksi')
            ->paginate(15);
        
        $items = Item::orderBy('nama_perangkat')->get();

        return view('transactions.index', compact('transactions', 'items'));
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
