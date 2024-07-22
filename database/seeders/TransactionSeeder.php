<?php

namespace Database\Seeders;

use App\Models\TransactionSales;
use App\Models\TransactionSalesDetail;
use App\Models\MasterBarang;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    function generateRandomSubtotal($min, $max, $increment)
    {
        $range = ($max - $min) / $increment;
        $randomIncrement = rand(0, (int) $range);
        return $min + $randomIncrement * $increment;
    }

    public function run()
    {
        $now = Carbon::now();
        $transactionCount = 100; // Number of transactions to seed

        // Define the start of the current month and today
        $startOfMonth = $now->copy()->startOfMonth(); // Start of the current month
        $endOfMonth = $now->copy()->endOfMonth(); // End of the current month
        $today = $now->copy()->endOfDay(); // Today’s end of the day

        // Ensure end of the range is today
        if ($endOfMonth->gt($today)) {
            $endOfMonth = $today;
        }

        // Retrieve all MasterBarang data
        $barangData = MasterBarang::all();

        // Create transactions and their details
        for ($i = 1; $i <= $transactionCount; $i++) {
            $kode = $now->format('Ym') . '-' . str_pad($i, 5, '0', STR_PAD_LEFT);

            // Generate a random date within the current month up to today
            $randomTimestamp = mt_rand($startOfMonth->timestamp, $endOfMonth->timestamp);
            $randomDate = Carbon::createFromTimestamp($randomTimestamp);

            // Generate details for this transaction
            $details = [];
            $detailsCount = rand(1, 5); // Number of details per transaction
            $subtotal = 0;

            foreach (range(1, $detailsCount) as $j) {
                $barang = $barangData->random();
                $qty = rand(1, min(10, $barang->qty)); // Ensure qty does not exceed available stock
                $harga = $barang->harga;
                $diskonNilai = $barang->diskon_pct ? ($harga * $barang->diskon_pct) / 100 : 0;
                $hargaDiskon = $harga - $diskonNilai;
                $totalHarga = $hargaDiskon * $qty;

                $details[] = [
                    'id_barang' => $barang->id,
                    'qty' => $qty,
                    'diskon_nilai' => $diskonNilai,
                    'harga_diskon' => $hargaDiskon,
                    'total_harga' => $totalHarga,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ];

                $subtotal += $totalHarga;
            }

            // Define diskon and ongkir
            $diskon = $this->generateRandomSubtotal(50000, 150000, 10000);
            $ongkir = $this->generateRandomSubtotal(10000, 60000, 10000);
            $totalBayar = $subtotal - $diskon + $ongkir;

            // Insert transaction record
            $transaction = TransactionSales::create([
                'kode' => $kode,
                'tgl' => $randomDate->toDateString(),
                'id_sales' => 1, // Replace with actual Sales IDs
                'id_costumer' => rand(3, 5), // Replace with actual Customer IDs
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'ongkir' => $ongkir,
                'total_bayar' => $totalBayar,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);

            // Insert details for the transaction
            foreach ($details as $detail) {
                TransactionSalesDetail::create(array_merge($detail, [
                    'id_transaction_sales' => $transaction->id,
                ]));
            }
        }
    }
}
