<?php

namespace Database\Seeders;

use App\Models\ProductionStage;
use App\Models\ProductionLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProductionStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ProductionLog::truncate();
        ProductionStage::truncate();
        Schema::enableForeignKeyConstraints();

        $stages = [
            ['name' => 'Antrian', 'sequence' => 1],
            ['name' => 'Print', 'sequence' => 2],
            ['name' => 'Press', 'sequence' => 3],
            ['name' => 'Pola', 'sequence' => 4],
            ['name' => 'Jahit', 'sequence' => 5],
            ['name' => 'Finish', 'sequence' => 6],
            ['name' => 'Siap Kirim', 'sequence' => 7],
            ['name' => 'Selesai', 'sequence' => 8],
        ];

        foreach ($stages as $stage) {
            ProductionStage::create($stage);
        }
    }
}
