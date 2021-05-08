<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Subcategoria;
use Faker\Generator as Faker;

$factory->define(Subcategoria::class, function (Faker $faker) {
	$categorias = App\Categoria::select('id')->get();
	$subcategorias = [
		'Martillo',
		'Cerrucho',
		'Otros',
		'Aceite',
		'Agua',
		'Marmol 1',
		'Marmol 2',
		'Marmol 3',
		'Madera 1',
		'Madera 2',
		'Madera 3',
	];
    return [
        'nombre' => $faker->randomElement($subcategorias),
		'descripcion' => $faker->paragraph,
		'categoria_id' => $faker->randomElement($categorias)
    ];
});
