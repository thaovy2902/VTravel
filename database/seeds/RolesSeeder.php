<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'OWNER', 'slug' => 'owner', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ADMIN', 'slug' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'USER', 'slug' => 'user', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
