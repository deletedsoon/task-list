<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(6, true),
        'user_id' => User::all()->random()->id,
    ];
});
