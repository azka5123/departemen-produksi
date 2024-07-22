<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_sales_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_transaction_sales');
            $table->foreignId('id_barang');
            $table->integer('qty');
            $table->decimal('diskon_nilai', 15, 2); //Diskon dalam rupiah
            $table->decimal('harga_diskon', 15, 2); //Harga Setelah Diskon
            $table->decimal('total_harga', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_sales_details');
    }
};
