<?php

namespace Database\Seeders;

use App\Models\OrderType;
use Illuminate\Database\Seeder;

class OrderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderType::create([
            'code' => 'SHP',
            'name' => 'Shopee',
            'description' => 'Pemesanan, Pembayaran dan Pengiriman melalui Shopee',
            'status' => 1,
        ]);

        OrderType::create([
            'code' => 'EXP',
            'name' => 'Ekspedisi',
            'description' => 'Pemesanan via Website, Pembayaran via Transfer Bank dan Pengiriman via Ekspedisi',
            'status' => 1,
        ]);
    }
}
