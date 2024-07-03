<?php

namespace Database\Seeders;

use App\Models\UserData;
use Illuminate\Database\Seeder;

class UserDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $total = 10000;
        $batchSize = 10000;
        for ($i = 0; $i < $total / $batchSize; $i++) {
            UserData::factory()->count($batchSize)->create();
        }
    }
}
