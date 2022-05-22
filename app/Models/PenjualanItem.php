<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanItem extends Model
{
    use HasFactory;

    protected $table = 'penjualan_item';

    protected $fillable = [
        'id_nota',
        'kode_barang',
        'qty',
    ];

    public function barang()
    {
        return $this->hasOne(Barang::class, 'kode', 'kode_barang');
    }
}
