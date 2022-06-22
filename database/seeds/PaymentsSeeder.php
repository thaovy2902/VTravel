<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsSeeder extends Seeder
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
                'payment_method' => 'Cash',
                'description' => 'Cash',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'payment_method' => 'VNPay',
                'description' => 'VNPay',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
