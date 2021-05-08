<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Presupuestos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->increments('id');
			$table->string('nombre')->nullable();
			$table->string('descripcion')->nullable();
			$table->date('fechaInicial');
			$table->date('fechaFinal');
			$table->float('cotizacion')->comment('precio total hasta la fecha limite');
			$table->enum('aprobado', ["Si", "No"])->default("No");
			$table->timestamps();

			$table->integer('cliente_id')->unsigned();
			$table->foreign("cliente_id")->references("id")
				->on("clientes")->onDelete("cascade")->onUpdate("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presupuestos');
    }
}
