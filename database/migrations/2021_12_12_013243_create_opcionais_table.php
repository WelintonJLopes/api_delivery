<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcionaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opcionais', function (Blueprint $table) {
            $table->id();
            $table->string('opcional', 100);
            $table->text('descricao')->nullable();
            $table->decimal('valor', $precision = 8, $scale = 2);
            $table->integer('minimo');
            $table->integer('maximo');
            $table->boolean('status');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('empresa_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('opcionais', function(Blueprint $table){
            $table->dropForeign('opcionais_user_id_foreign');
            $table->dropForeign('opcionais_empresa_id_foreign');
        });

        Schema::dropIfExists('opcionais');
    }
}
