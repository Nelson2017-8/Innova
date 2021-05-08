<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Categoria;
use Faker\Generator as Faker;

$factory->define(Categoria::class, function (Faker $faker) {
    $categorias = [
    	'Pinturas',
		'Clavos',
		'Otros',
		'Madera',
		'Marmól',
		'Tinner',
		'Herramientas',
	];
	return [
        'nombre' => $faker->randomElement($categorias),
		'descripcion' => $faker->text,
    ];
});
