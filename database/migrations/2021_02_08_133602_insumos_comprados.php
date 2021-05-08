<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsumosComprados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumos_comprados', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->integer('cantidad');
			$table->float('precioUnitario');
			$table->string('codigo_barras', 100)->nullable();
			$table->string('detalles')->nullable();
			$table->integer('subcategoria_id')->unsigned();
			$table->integer('compra_id')->unsigned();
			$table->timestamps();

			$table->foreign("subcategoria_id")->references("id")
				->on("subcategoria")->onDelete("cascade")->onUpdate("cascade");

            $table->foreign('compra_id')->references('id')->on('compras')
				->onDelete("cascade")->onUpdate("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insumos_comprados');
    }
}
