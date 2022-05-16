<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@laudable-me.test',
            'password' => bcrypt('admin123'),
            'role' => 'Admin'
        ]);

        User::create([
            'name' => 'Staff',
            'email' => 'staff@laudable-me.test',
            'password' => bcrypt('staff123'),
            'role' => 'Staff'
        ]);
    }
}
