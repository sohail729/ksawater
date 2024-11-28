<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(AdminSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(CarTypeSeeder::class);
        $this->call(CarBrandSeeder::class);
        $this->call(CarModelSeeder::class);
    }
}
