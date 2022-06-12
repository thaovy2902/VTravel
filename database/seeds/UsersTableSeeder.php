<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Owner',
                'email' => 'owner@owner.com',
                'password' => bcrypt('123456'),
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123456'),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // factory(App\Models\User::class, 100)->create();
    }
}
