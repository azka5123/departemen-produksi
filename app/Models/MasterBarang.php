<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'harga',
        'qty',
        'diskon_pct',
    ];
}
