<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cliente;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Cliente::class, function (Faker $faker) {
    return [
        'primerNombre' => $faker->firstName,
		'primerApellido' => $faker->lastName,
		'cedula' => 'V-'.random_int(10, 40).random_int(100000, 999999),
		'correo' => $faker->unique()->safeEmail,
		'telefono_1' => '+58 0416'.random_int(1000000, 9999999),
		'telefono_2' => '+58 0426'.random_int(1000000, 9999999),
		'direccion' => $faker->address,
    ];
});
