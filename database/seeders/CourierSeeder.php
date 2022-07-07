<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Courier::create([
            'code' => 'jne',
            'name' => 'JNE',
            'description' => 'Jalur Nugraha Ekakurir (JNE)',
            'status' => 1,
        ]);

        Courier::create([
            'code' => 'tiki',
            'name' => 'TIKI',
            'description' => 'Titipan Kilat (TIKI)',
            'status' => 1,
        ]);

        Courier::create([
            'code' => 'pos',
            'name' => 'POS',
            'description' => 'POS Indonesia',
            'status' => 1,
        ]);
    }
}
