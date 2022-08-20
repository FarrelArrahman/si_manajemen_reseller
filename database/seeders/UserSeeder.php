<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Reseller;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    // User otomatis aktif (true) atau tidak (false)
    private $status = true;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $city = [17,32,94,114,128,161,170,197,447];
        $faker = Faker::create('id_ID');
        
        User::create([
            'name' => 'Admin',
            'email' => 'admin@laudable-me.com',
            'password' => bcrypt('admin123'),
            'role' => 'Admin',
            'status' => 1,
            'photo' => 'public/user-default.png',
        ]);

        User::create([
            'name' => 'Staff',
            'email' => 'staff@laudable-me.com',
            'password' => bcrypt('staff123'),
            'role' => 'Staff',
            'status' => 1,
            'photo' => 'public/user-default.png',
        ]);

        // Reseller
        for($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => 'reseller' . $i . '@laudable-me.com',
                'password' => bcrypt('reseller123'),
                'role' => 'Reseller',
                'status' => 1,
                'photo' => 'public/user-default.png',
            ]);

            $bank = [
                ['name' => 'BANK BNI', 'code' => '009'],
                ['name' => 'BANK BRI', 'code' => '002'],
                ['name' => 'BANK MANDIRI', 'code' => '008'],
                ['name' => 'BANK BCA', 'code' => '014'],
            ];

            $rand = rand(0,3);

            $reseller = Reseller::create([
                'user_id' => $user->id,
                'shop_name' => $faker->company,
                'shop_address' => $faker->address,
                'province' => 1,
                'city' => $city[rand(0,8)],
                'postal_code'  => $faker->postCode,
                'phone_number'  => $faker->phoneNumber,
                'social_media'  => null,
                'account_number' => rand(1000000000,9999999999),
                'bank_name' => $bank[$rand]['name'],
                'bank_code' => $bank[$rand]['code'],
                'account_holder_name' => $user->name,
                'rejection_reason' => NULL,
                'reseller_status' => $this->resellerStatus($this->status),
                'approval_date' => $this->approvalDate($this->status),
                'approved_by' => 1,
            ]);
        }
    }

    public function resellerStatus($status) {
        return $status ? 'AKTIF' : 'PENDING';
    }

    public function approvalDate($status) {
        return $status ? now() : null;
    }
}
