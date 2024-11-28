<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carModels = [
            [3, 'Altima'],
            [3, '350Z'],
            [3, 'Rogue'],
            [3, 'Leaf'],
            [4, 'Sonata'],
            [4, 'Genesis Coupe'],
            [4, 'Tucson'],
            [4, 'Kona Electric'],
            [5, 'Mazda6'],
            [5, 'Mazda3'],
            [5, 'CX-5'],
            [5, 'MX-30'],
            [6, 'Prius'],
            [6, 'Camry'],
            [6, 'RAV4'],
            [6, 'Corolla'],
            [7, 'C-Class'],
            [7, 'E-Class'],
            [7, 'GLC-Class'],
            [7, 'EQC'],
        ];

        foreach ($carModels as $model) {
            DB::table('car_models')->insert([
                'brand_id' => $model[0],
                'name' => $model[1],
            ]);
        }
    }
}
