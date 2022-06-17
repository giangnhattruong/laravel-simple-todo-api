<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Todo;
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
        // \App\Models\User::factory(10)->create();
        Todo::factory(9)->create();

        Color::create([
            'name' => 'Green'
        ]);
        Color::create([
            'name' => 'Blue'
        ]);
        Color::create([
            'name' => 'Orange'
        ]);
        Color::create([
            'name' => 'Purple'
        ]);
        Color::create([
            'name' => 'Red'
        ]);
    }
}
