<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Slide;
use Faker\Generator as Faker;

$factory->define(Slide::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(),
        'image' => 'https://storage.cloud.google.com/graduation-4e427.appspot.com/images/slides/1582443113830.webp',
        'link' => $faker->url(),
        'is_active' => $faker->boolean(),
    ];
});
