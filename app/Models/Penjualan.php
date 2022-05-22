<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penjualan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penjualan';

    protected $fillable = [
        'id_nota',
        'tgl',
        'kode_pelanggan',
        'subtotal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'kode_pelanggan', 'id');
    }

    public function penjualan_item()
    {
        return $this->hasMany(PenjualanItem::class, 'id_nota', 'id_nota');
    }
}
