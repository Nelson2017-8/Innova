<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsumosAlmacenados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumos_almacenados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('insumo_compra')->unsigned();
            $table->integer('almacen_id')->unsigned();
            $table->timestamps();

            $table->foreign("almacen_id")->references("id")
				->on("almacen")->onDelete("cascade")->onUpdate("cascade");

            $table->foreign("insumo_compra")->references("id")
				->on("insumos_comprados")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insumos_almacenados');
    }
}
