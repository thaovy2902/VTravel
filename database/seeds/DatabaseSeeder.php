<?php

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
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            CategoriesSeeder::class,
            PaymentsSeeder::class,
            // ToursSeeder::class,
            // RatingsSeeder::class,
            // OrdersSeeder::class
        ]);

        // $this->call(SlidesSeeder::class);
    }
}
