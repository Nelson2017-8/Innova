<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Sucursal;
use Faker\Generator as Faker;

$factory->define(Sucursal::class, function (Faker $faker) {
	$sucursal = [
		'Sede Principal',
		'Sede Secundaria 1',
		'Sede Secundaria 2',
		'Sede Secundaria 3',
	];
    return [
        'nombre' => $sucursal[random_int(0,3)],
		'ubicacion' => $faker->address,
    ];
});
