<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CarBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            'Renault',
            'Kia',
            'Nissan',
            'Hyundai',
            'Mazda',
            'Toyota',
            'Mercedes Benz',
            'Mitsubishi',
            'Chevrolet',
            'Land Rover',
            'Lincoln',
            'BMW',
            'Rolls Royce',
            'Exeed',
            'Infiniti',
            'Peugeot',
            'Lamborghini',
            'Tesla',
            'Mini',
            'Ferrari',
            'Bentley',
            'Audi',
            'Porsche',
            'Ford',
            'Cadillac',
            'Volkswagen',
            'Hongqi',
            'Lexus',
            'Chrysler',
            'MG',
            'Dodge',
            'McLaren',
            'Jeep',
            'Aston Martin',
            'Genesis',
            'GMC',
            'Maserati',
            'Honda',
            'Citroen',
            'Opel',
            'Jaguar',
            'JAC',
            'Jetour',
            'Fiat',
            'Suzuki',
            'Volvo',
            'Polaris',
            'GAC',
            'Polestar',
            'Bugatti',
            'Haval',
            'Geely',
            'Forthing',
            'King Long',
            'Subaru',
            'Ineos',
            'Kaiyi',
        ];

        foreach ($brands as $brand) {
            DB::table('car_brands')->insert([
                'name' => $brand,
                'is_top' => rand(0, 1)
            ]);
        }
    }
}
