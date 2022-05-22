<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kode',
        'nama_barang',
        'kategori',
        'harga',
    ];
}
