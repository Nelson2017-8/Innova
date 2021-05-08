<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


$factory->define(User::class, function (Faker $faker) {
    return [
        'username' => 'root',
        'typeuser' => 'Root',
        'email' => 'admin@root.com',
        'password' => md5('12345678'), // password
        'remember_token' => 'false',
    ];
});
