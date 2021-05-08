<?php

use App\Proveedor;
use Faker\Generator as Faker;

$factory->define(Proveedor::class, function (Faker $faker) {
	$name = $faker->company;
    return [
        'nombre' => $faker->name,
		'razonSocial' => $faker->company,
		'correo' => $faker->companyEmail,
		'cod_postal' => $faker->postcode,
		'telefono_1' => $faker->phoneNumber,
		'telefono_2' => $faker->phoneNumber,
		'direccion' => $faker->address,
    ];
});
