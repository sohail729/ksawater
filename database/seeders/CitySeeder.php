<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $cities = json_decode(file_get_contents(public_path('nl-cities.json')));

        foreach ($cities as $city) {
            DB::table('cities')->insert(['name' => $city->name]);
        }

    }
}
