<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Almacen;
use Faker\Generator as Faker;

$factory->define(Almacen::class, function (Faker $faker) {
    return [
        'nombre' => 'Almacen '.random_int(1,1000),
		'sucursal_id' => $faker->randomElement( App\Sucursal::select('id')->get() ),
		'descripcion' => $faker->paragraph,
    ];
});
