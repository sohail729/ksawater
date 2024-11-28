<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            "Sedan",
            "SUV",
            "Hatchback",
            "Coupe",
            "Convertible",
            "Minivan",
            "Pickup",
            "Crossover",
            "Wagon",
            "Roadster",
            "EV",
            "Luxury"
        ];

        foreach ($types as $type) {
            DB::table('car_types')->insert([
                'name' => $type
            ]);
        }
    }
}
