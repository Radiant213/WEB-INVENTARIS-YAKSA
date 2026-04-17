<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $readyItems = Item::where('status', 'Ready')->count();
        $keluarItems = Item::where('status', 'Barang Keluar')->count();
        $rmaItems = Item::where('status', 'Barang RMA')->count();

        $items = Item::latest()->get();
        $transactions = Transaction::with(['item', 'user'])->latest('tanggal_transaksi')->get();

        $statusDistribution = Item::selectRaw('status, count(*) as total')
            ->groupBy('status')->get();

        return view('laporan.index', compact(
            'totalItems', 'readyItems', 'keluarItems', 'rmaItems',
            'items', 'transactions', 'statusDistribution'
        ));
    }

    public function export()
    {
        return Excel::download(new ItemsExport, 'inventaris-yaksa-' . date('Y-m-d') . '.xlsx');
    }
}
