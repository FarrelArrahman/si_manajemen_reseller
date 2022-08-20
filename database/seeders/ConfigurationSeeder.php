<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configuration::insert([
            // [
            //     'config_name' => 'address',
            //     'value' => 'Jl. Cargo Permai No. 1, Ubung, Denpasar Utara, Denpasar, Bali',
            // ],
            // [
            //     'config_name' => 'province',
            //     'value' => 1,
            // ],
            // [
            //     'config_name' => 'city',
            //     'value' => 114,
            // ],
            // [
            //     'config_name' => 'postal_code',
            //     'value' => 80111,
            // ],
            // [
            //     'config_name' => 'customer_service_phone_number',
            //     'value' => '089654989993',
            // ],
            // [
            //     'config_name' => 'account_number',
            //     'value' => '1122334455',
            // ],
            // [
            //     'config_name' => 'bank_name',
            //     'value' => 'BANK BNI',
            // ],
            // [
            //     'config_name' => 'bank_code',
            //     'value' => '009',
            // ],
            // [
            //     'config_name' => 'account_holder_name',
            //     'value' => 'Rekening BNI Laudable',
            // ],
            // [
            //     'config_name' => 'auth_background_image',
            //     'value' => 'public/auth-bg.jpg'
            // ],
            // [
            //     'config_name' => 'email',
            //     'value' => 'admin@laudable-me.com'
            // ],
            [
                'config_name' => 'help',
                'value' => '<h1>Test Judul</h1>'
            ]
        ]);
    }
}
