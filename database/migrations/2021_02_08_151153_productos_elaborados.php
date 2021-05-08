<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductosElaborados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos_elaborados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('presupuesto_id')->unsigned();
            $table->integer('subcategoria_id')->unsigned();
            $table->integer('almacen_id')->unsigned()->nullable();
            $table->date('fechaElaboracion');
            $table->float('precio');
            $table->string('cod_barras')->nullable();
            $table->timestamps();


            $table->foreign("almacen_id")->references("id")->on("almacen")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("subcategoria_id")->references("id")->on("subcategoria")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("presupuesto_id")->references("id")->on("presupuestos")->onDelete("cascade")->onUpdate("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos_elaborados');
    }
}
