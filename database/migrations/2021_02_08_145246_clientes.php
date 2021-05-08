<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Clientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('primerNombre', 50);
            $table->string('primerApellido', 50);
            $table->string('cedula', 20)->unique();
            $table->string('correo', 150)->unique();
			$table->string('telefono_1', 20);
			$table->string('telefono_2', 20)->nullable();
			$table->string('direccion')->nullable();
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
