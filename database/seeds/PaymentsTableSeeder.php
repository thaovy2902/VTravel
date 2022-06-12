<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payments')->insert([
            [
                'payment_method' => 'Trực tiếp',
                'description' => 'Thanh toán trực tiếp',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'payment_method' => 'Online',
                'description' => 'Thanh toán online',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
