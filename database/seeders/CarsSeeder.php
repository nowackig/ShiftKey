<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('cars')->insert([
            [
                'id' => 1,
                'uuid' => 'uuid_1',
                'user_id' => 1,
                'make' => 'Land Rover',
                'model' => 'Range Rover Sport',
                'year' => 2017,
            ],
            [
                'id' => 2,
                'uuid' => 'uuid_2',
                'user_id' => 1,
                'make' => 'Ford',
                'model' => 'F150',
                'year' => 2014,
            ],
            [
                'id' => 3,
                'uuid' => 'uuid_3',
                'user_id' => 1,
                'make' => 'Chevy',
                'model' => 'Tahoe',
                'year' => 2015,
            ],
            [
                'id' => 4,
                'uuid' => 'uuid_4',
                'user_id' => 1,
                'make' => 'Aston Martin',
                'model' => 'Vanquish',
                'year' => 2018,
            ],
        ]);
    }
}
