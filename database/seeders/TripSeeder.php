<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('cars')->insert([
            [
                'date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'miles' => 6.8,
                'total' => 22.1,
                'car_uuid' => 'car_uuid_123454321',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
