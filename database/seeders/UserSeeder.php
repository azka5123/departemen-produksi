<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode' => 'A001',
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'telp' => '0812345',
                'password' => Hash::make('123'),
                'role' => 'admin',
            ],
            [
                'kode' => 'S001',
                'name' => 'Sales',
                'email' => 'sales@example.com',
                'telp' => '0812312445',
                'password' => Hash::make('123'),
                'role' => 'sales',
            ],
            [
                'kode' => 'C001',
                'name' => 'Costumer1',
                'email' => 'costumer1@example.com',
                'telp' => '08123451215',
                'password' => null,
                'role' => 'costumer',
            ],
            [
                'kode' => 'C002',
                'name' => 'Costumer2',
                'email' => 'costumer2@example.com',
                'telp' => '081234598765',
                'password' => null,
                'role' => 'costumer',
            ],
            [
                'kode' => 'C003',
                'name' => 'Costumer3',
                'email' => 'costumer3@example.com',
                'telp' => '0812349512',
                'password' => null,
                'role' => 'costumer',
            ],
        ];

        foreach ($data as $item) {
            DB::table('users')->insert([
                'kode' => $item['kode'],
                'name' => $item['name'],
                'email' => $item['email'],
                'telp' => $item['telp'],
                'password' => $item['password'],
                'role' => $item['role'],
            ]);
        }
    }
}
