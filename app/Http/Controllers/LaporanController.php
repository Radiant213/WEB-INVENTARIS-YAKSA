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
        $transQuery = Transaction::with(['item', 'user'])->orderByDesc('tanggal_transaksi')->latest('id');
        
        if ($activeGudang !== 'universal') {
            $itemQuery->where('gudang', $activeGudang);
            $transQuery->whereHas('item', function($q) use ($activeGudang) {
                $q->where('gudang', $activeGudang);
            });
        }

        // Filter rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $itemQuery->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
            $transQuery->whereBetween('tanggal_transaksi', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $itemQuery->where('created_at', '>=', $request->start_date . ' 00:00:00');
            $transQuery->where('tanggal_transaksi', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $itemQuery->where('created_at', '<=', $request->end_date . ' 23:59:59');
            $transQuery->where('tanggal_transaksi', '<=', $request->end_date);
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
