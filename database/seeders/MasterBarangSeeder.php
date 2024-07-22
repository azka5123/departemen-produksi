<?php

namespace Database\Seeders;

use App\Models\MasterBarang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        function generateUniqueKode($i)
        {
            do {
                $kode = chr(rand(65, 90)) . str_pad($i, 3, '0', STR_PAD_LEFT);
                $exists = MasterBarang::where('kode', $kode)->exists();
            } while ($exists);

            return $kode;
        }
        $data = [];
        $basePrice = 100000;
        $priceIncrement = 25000;
        $numberOfItems = 10;

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $data[] = [
                'kode' => generateUniqueKode($i),
                'nama' => 'Barang ' . chr(64 + $i),
                'harga' => $basePrice + $priceIncrement * ($i - 1),
                'qty' => rand(50, 100),
                'diskon_pct' => rand(1, 25),
            ];
        }

        DB::table('master_barangs')->insert($data);
    }
}
