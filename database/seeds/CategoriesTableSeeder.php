<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Trong nước', 'slug' => 'trong-nuoc', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ngoài nước', 'slug' => 'ngoai-nuoc', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
