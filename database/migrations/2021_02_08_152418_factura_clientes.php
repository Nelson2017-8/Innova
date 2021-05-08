<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FacturaClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('fechaEmision');
            $table->float('precioTotal');
            $table->string('numFactura', 50)->unique();

            $table->integer('cliente_id')->unsigned();
            $table->foreign("cliente_id")->references("id")->on("clientes")->onDelete("cascade")->onUpdate("cascade");


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
        Schema::dropIfExists('factura_clientes');
    }
}
