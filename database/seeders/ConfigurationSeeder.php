<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        Configuration::create([
            'address' => 'Jl. Cargo Permai No. 1, Ubung, Denpasar Utara, Denpasar, Bali',
            'province' => 1,
            'city' => 114,
            'postal_code' => 80111,
            'customer_service_phone_number' => '089654989993',
            'account_number' => '1122334455',
            'bank_name' => 'BANK BNI',
            'bank_code' => '009',
            'account_holder_name' => $faker->name,
            'auth_background_image' => 'public/auth-bg.jpg'
        ]);
    }
}
