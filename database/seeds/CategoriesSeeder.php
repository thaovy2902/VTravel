<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Domestic Tourism', 'slug' => 'domestic', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Travel Abroad', 'slug' => 'aboard', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
