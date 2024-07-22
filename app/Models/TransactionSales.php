<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionSales extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'tgl',
        'id_sales',
        'id_costumer',
        'subtotal',
        'diskon',
        'ongkir',
        'total_bayar',
    ];

    public function rCostumer(){
        return $this->belongsTo(User::class, 'id_costumer');
    }

    public function rDetails(){
        return $this->hasMany(TransactionSalesDetail::class, 'id_transaction_sales');
    }
}
