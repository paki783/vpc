<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
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
        'user_name' => $faker->userName,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$VcNnDKtjHUzYTiUddolv9Of09uW/F2wwL.EqhmuPe2crCAagdDQpW',
        'xboxtag' => $faker->userName,
        'streamid' => $faker->userName,
        'profile_image' => $faker->imageUrl(800, 400, 'cats', true, 'Faker', true),
    ];
});
