<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use WalkerChiu\MorphWeb\Models\Entities\Web;
use WalkerChiu\MorphWeb\Models\Entities\WebLang;

$factory->define(Web::class, function (Faker $faker) {
    return [
        'morph_type' => '',
        'morph_id'   => 1,
        'type'       => $faker->randomElement(['web', 'blog', 'facebook', 'instagram', 'twitter']),
        'serial'     => $faker->isbn10,
        'url'        => $faker->url
    ];
});

$factory->define(WebLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'description']),
        'value' => $faker->sentence
    ];
});
