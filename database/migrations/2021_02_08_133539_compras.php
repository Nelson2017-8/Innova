<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Compras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->increments('id')->comment('Compras a los proveedores');
			$table->integer('proveedor_id')->unsigned();
			$table->string('numFactura', 150)->unique();
			$table->float('precioCompra')->comment('total de la compra')->nullable();
			$table->dateTime('fechaCompra');
			$table->date('limitGarantia')->nullable()->comment('Días de la garantia ejmplo: 30 días ');
            $table->timestamps();

            $table->foreign("proveedor_id")->references("id")->on("proveedores")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras');
    }
}
