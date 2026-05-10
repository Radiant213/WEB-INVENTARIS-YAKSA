<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['item_id', 'user_id', 'tipe_transaksi', 'pengirim', 'penerima', 'tanggal_transaksi'])]
class Transaction extends Model
{
    use HasFactory;

    protected $casts = [
        'tanggal_transaksi' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
