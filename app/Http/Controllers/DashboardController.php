<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $readyItems = Item::where('status', 'Ready')->count();
        $keluarItems = Item::where('status', 'Barang Keluar')->count();
        $rmaItems = Item::where('status', 'Barang RMA')->count();
        $rusakItems = Item::where('status', 'Barang Rusak')->count();
        
        $masukItems = Transaction::where('tipe_transaksi', 'in')->count();

        $recentTransactions = Transaction::with(['item', 'user'])
            ->orderByDesc('tanggal_transaksi')
            ->latest('id')
            ->take(5)
            ->get();

        // Distribusi status untuk mini chart
        $statusDistribution = Item::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();

        // Distribusi lokasi
        $lokasiDistribution = Item::selectRaw('lokasi_device, count(*) as total')
            ->whereNotNull('lokasi_device')
            ->groupBy('lokasi_device')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalItems',
            'readyItems',
            'keluarItems',
            'rmaItems',
            'rusakItems',
            'masukItems',
            'recentTransactions',
            'statusDistribution',
            'lokasiDistribution'
        ));
    }
}
