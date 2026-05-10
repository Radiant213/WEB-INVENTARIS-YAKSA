<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Item;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        // Get pending transactions
        $pendingTransactions = Transaction::with(['item.category', 'user'])
            ->where('status', 'pending')
            ->latest('created_at')
            ->paginate(15, ['*'], 'tx_page');

        // Get pending items (master barang)
        $pendingItems = Item::with(['category', 'user'])
            ->where('status_approval', 'pending')
            ->latest('created_at')
            ->paginate(15, ['*'], 'item_page');

        return view('approvals.index', compact('pendingTransactions', 'pendingItems'));
    }

    public function approve(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi sudah diproses sebelumnya.');
        }

        $transaction->update(['status' => 'approved']);

        // Update item status when transaction is approved
        if ($transaction->tipe_transaksi === 'in') {
            $transaction->item->update(['status' => 'Ready']);
        } else {
            $transaction->item->update(['status' => 'Barang Keluar']);
        }

        // Send notification to the user who requested it
        $type = $transaction->tipe_transaksi === 'in' ? 'Masuk' : 'Keluar';
        Notification::create([
            'type' => 'transaction_' . $transaction->tipe_transaksi,
            'title' => "Approval Diterima",
            'message' => "Request barang {$type} ({$transaction->item->nama_perangkat}) disetujui oleh " . Auth::user()->name,
            'link' => route('transactions.index'),
        ]);

        return back()->with('success', 'Transaksi berhasil disetujui.');
    }

    public function reject(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi sudah diproses sebelumnya.');
        }

        $transaction->update(['status' => 'rejected']);

        $type = $transaction->tipe_transaksi === 'in' ? 'Masuk' : 'Keluar';
        Notification::create([
            'type' => 'approval_request',
            'title' => "Approval Ditolak",
            'message' => "Request barang {$type} ({$transaction->item->nama_perangkat}) ditolak oleh " . Auth::user()->name,
            'link' => route('transactions.index'),
        ]);

        return back()->with('success', 'Transaksi telah ditolak.');
    }

    public function approveItem(Item $item)
    {
        if ($item->status_approval !== 'pending') {
            return back()->with('error', 'Barang sudah diproses sebelumnya.');
        }

        $item->update(['status_approval' => 'approved']);

        Notification::create([
            'type' => 'item_created',
            'title' => 'Barang Disetujui',
            'message' => "Barang {$item->nama_perangkat} ({$item->serial_number}) disetujui oleh " . Auth::user()->name,
            'link' => route('items.index'),
        ]);

        return back()->with('success', 'Barang berhasil disetujui dan masuk ke Master Barang.');
    }

    public function rejectItem(Item $item)
    {
        if ($item->status_approval !== 'pending') {
            return back()->with('error', 'Barang sudah diproses sebelumnya.');
        }

        $item->update(['status_approval' => 'rejected']);

        Notification::create([
            'type' => 'approval_request',
            'title' => 'Penambahan Barang Ditolak',
            'message' => "Barang {$item->nama_perangkat} ({$item->serial_number}) ditolak oleh " . Auth::user()->name,
            'link' => route('items.index'),
        ]);

        return back()->with('success', 'Request penambahan barang ditolak.');
    }
}
