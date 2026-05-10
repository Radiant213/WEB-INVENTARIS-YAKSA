<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Riwayat transaksi barang (masuk/keluar) oleh user ini
        $transactions = Transaction::with(['item.category'])
            ->where('user_id', $user->id)
            ->latest('tanggal_transaksi')
            ->paginate(10, ['*'], 'tx_page');

        // Riwayat pembuatan barang oleh user ini
        $createdItems = Item::with('category')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10, ['*'], 'item_page');

        return view('riwayat.index', compact('transactions', 'createdItems'));
    }
}
