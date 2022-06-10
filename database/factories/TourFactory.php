<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tour;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Tour::class, function (Faker $faker) {
    return [
        'code' => $faker->unixTime(),
        'name' => $faker->name,
        'slug' => Str::slug($faker->name),
        'image' => $faker->imageUrl($width = 640, $height = 480),
        'gallery' => $faker->shuffle(array(1, 2, 3)),
        'depart' => 'daily',
        'description' => $faker->randomHtml(2, 3),
        'from_place' => $faker->numberBetween(1, 94),
        'to_place' => $faker->numberBetween(1, 94),
        'transport' => 'car',
        'number_days' => $faker->randomDigit(),
        'number_persons' => $faker->randomDigit(),
        'price_default' => $faker->numberBetween(1000, 20000),
        'price_children' => $faker->numberBetween(1000, 15000),
        'price_baby' => $faker->numberBetween(1000, 10000),
        'is_featured' => $faker->boolean(),
        'is_active' => $faker->boolean(),
        'category_id' => $faker->numberBetween(1, 2),
    ];
});
