<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductosMateriales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumos_usados', function (Blueprint $table) {
            $table->increments('id')->comment('insumos o materia prima usada para elaborar un producto');
			$table->integer('cantidad')->comment('cantidad usada');
			$table->integer('insumo_id')->unsigned();
            $table->integer('presupuestos_id')->unsigned();
            $table->timestamps();

            $table->foreign("insumo_id")->references("id")->on("insumos_almacenados")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("presupuestos_id")->references("id")->on("presupuestos")->onDelete("cascade")->onUpdate("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insumos_usados');
    }
}
