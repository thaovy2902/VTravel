<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'code' => $faker->unixTime,
        'customer_name' => $faker->name,
        'customer_email' => $faker->safeEmail,
        'customer_phone_number' => $faker->randomDigit,
        'customer_address' => $faker->randomDigit,
        'date_depart' => $faker->date($format = 'Y-m-d'),
        'quantity_people' => $faker->randomDigit,
        'quantity_children' => $faker->randomDigit,
        'quantity_baby' => $faker->randomDigit,
        'total_amount' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL),
        'note' => $faker->sentence,
        'status' => $faker->numberBetween(1, 4),
        'reason_cancel' => $faker->sentence,
        'payment_id' => $faker->numberBetween(1, 2),
        'tour_id' => $faker->numberBetween(1, 100),
        'user_id' => $faker->numberBetween(1, 100),
    ];
});
