<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionSalesDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_transaction_sales',
        'id_barang',
        'qty',
        'diskon_nilai',
        'harga_diskon',
        'total_harga',
    ];

    public function rTransactionSales()
    {
        return $this->belongsTo(TransactionSales::class, 'id_transaction_sales');
    }

    public function rMasterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'id_barang');
    }
}
