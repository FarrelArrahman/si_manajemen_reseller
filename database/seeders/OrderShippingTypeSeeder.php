<?php

namespace Database\Seeders;

use App\Models\OrderShippingType;
use Illuminate\Database\Seeder;

class OrderShippingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderShippingType::create([
            'name' => 'Shopee',
            'description' => 'Pengiriman via Link Khusus Shopee',
            'status' => 1,
        ]);

        OrderShippingType::create([
            'name' => 'Ekspedisi',
            'description' => 'Pengiriman via Ekspedisi',
            'status' => 1,
        ]);
    }
}
