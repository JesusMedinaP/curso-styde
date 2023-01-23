<?php

use Faker\Generator as Faker;

$factory->define(App\UserProfiles::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\User::class),
        'bio' => $faker->paragraph,
    ];
});
