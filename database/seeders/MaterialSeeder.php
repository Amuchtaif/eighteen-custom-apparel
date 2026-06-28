<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            ['name' => 'Dryfit', 'description' => 'Bahan dryfit berpori, cocok untuk jersey olahraga.'],
            ['name' => 'Serena', 'description' => 'Bahan serena halus dan lentur, ideal untuk printing jersey.'],
            ['name' => 'Cotton Combed', 'description' => 'Bahan katun combed 30s berkualitas tinggi dan adem.'],
        ];

        foreach ($materials as $material) {
            Material::firstOrCreate(
                ['name' => $material['name']],
                ['description' => $material['description']]
            );
        }
    }
}
