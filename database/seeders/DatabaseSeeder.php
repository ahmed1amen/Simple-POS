<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Accountant',
            'email' => 'admin@steslam.com',
            'password' => '$2y$10$I38OBw3hqEtA5XnRZlh0p.YdSZPgdQXR.mmHCiIUgN6LPvoziCi9a',
                                              ]);
    }
}
