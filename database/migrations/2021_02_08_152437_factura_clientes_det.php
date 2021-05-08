<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FacturaClientesDet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_clientes_det', function (Blueprint $table) {
            $table->increments('id');
            $table->float('precioUnitario');
            $table->integer('cantidad');
            
            $table->integer('producto_elaborado_id')->unsigned();
            $table->foreign("producto_elaborado_id")->references("id")->on("productos_elaborados")->onDelete("cascade")->onUpdate("cascade");
            
            $table->integer('factura_id')->unsigned();
            $table->foreign("factura_id")->references("id")->on("factura_clientes")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('factura_clientes_det');
    }
}
