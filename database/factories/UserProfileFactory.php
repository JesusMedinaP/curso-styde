<?php

use Faker\Generator as Faker;

$factory->define(App\UserProfiles::class, function (Faker $faker) {
    return [
        'bio' => $faker->paragraph,
    ];
});
