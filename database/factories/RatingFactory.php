<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Rating;
use Faker\Generator as Faker;

$factory->define(Rating::class, function (Faker $faker) {
    return [
        'scores' => $faker->numberBetween(1, 5),
        'content' => $faker->text(),
        'is_active' => $faker->boolean(),
        'tour_id' => $faker->numberBetween(1, 100),
        'user_id' => $faker->numberBetween(1, 100),
    ];
});
