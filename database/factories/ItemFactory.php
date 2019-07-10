<?php

/** @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->name . '物品',
        'description' => $faker->realText(20),
    ];
});
