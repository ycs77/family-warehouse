<?php

/** @var $factory \Illuminate\Database\Eloquent\Factory */

use App\History;
use Faker\Generator as Faker;

$factory->define(History::class, function (Faker $faker) {
    return [
        'action' => 'borrow',
    ];
});
