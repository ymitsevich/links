<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => Str::random(30),
        'remember_token' => Str::random(10),
        'api_token' => Str::random(30),
    ];
});

$factory->define(\App\Links\CompressedLink::class, function (Faker $faker) {
    return [
        'link' => $faker->word,
    ];
});
