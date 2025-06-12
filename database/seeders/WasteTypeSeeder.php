<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WasteType;

class WasteTypeSeeder extends Seeder
{
    public function run()
    {
        $wasteTypes = [
            [
                'name' => 'Besi',
                'price_per_kg' => 2000,
                'description' => 'Sampah logam besi'
            ],
            [
                'name' => 'Kardus',
                'price_per_kg' => 1500,
                'description' => 'Kardus bekas'
            ],
            [
                'name' => 'Gelas Plastik',
                'price_per_kg' => 1000,
                'description' => 'Gelas plastik bekas'
            ]
        ];

        foreach ($wasteTypes as $type) {
            WasteType::create($type);
        }
    }
}
