<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       User::create([
            'type' => 'admin',
            'package' => '0',
            'username' => 'admin',
            'fullname' => 'Admin',
            'email' => 'now2rent@icloud.com',
            'password' => 12345678,
            'status' => 1
       ]);
    }
}
