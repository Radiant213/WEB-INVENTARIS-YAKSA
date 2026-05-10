<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $activeGudang = $request->get('gudang', 'universal');
        
        $itemQuery = Item::query();
        $transQuery = Transaction::with(['item', 'user'])->latest('tanggal_transaksi');
        
        if ($activeGudang !== 'universal') {
            $itemQuery->where('gudang', $activeGudang);
            $transQuery->whereHas('item', function($q) use ($activeGudang) {
                $q->where('gudang', $activeGudang);
            });
        }

        $totalItems = (clone $itemQuery)->count();
        $readyItems = (clone $itemQuery)->where('status', 'Ready')->count();
        $keluarItems = (clone $itemQuery)->where('status', 'Barang Keluar')->count();
        $rmaItems = (clone $itemQuery)->where('status', 'Barang RMA')->count();
        $rusakItems = (clone $itemQuery)->where('status', 'Barang Rusak')->count();
        
        $masukQuery = clone $transQuery;
        $masukItems = $masukQuery->where('tipe_transaksi', 'in')->count();

        $items = (clone $itemQuery)->latest()->get();
        $transactions = $transQuery->get();

        $statusDistribution = (clone $itemQuery)->selectRaw('status, count(*) as total')
            ->groupBy('status')->get();

        return view('laporan.index', compact(
            'totalItems', 'readyItems', 'keluarItems', 'rmaItems', 'rusakItems', 'masukItems',
            'items', 'transactions', 'statusDistribution', 'activeGudang'
        ));
    }

    public function export(Request $request)
    {
        $gudang = $request->get('gudang', 'universal');
        $filename = 'inventaris-yaksa-' . $gudang . '-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new ItemsExport($gudang), $filename);
    }
}
