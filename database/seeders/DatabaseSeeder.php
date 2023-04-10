<?php

declare(strict_types=1);

use Database\Seeders\CarsSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(CarsSeeder::class);
    }
}
