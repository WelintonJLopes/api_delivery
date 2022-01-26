<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas_categorias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('categoria_id');

            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('categoria_id')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas_categorias', function(Blueprint $table){
            $table->dropForeign('empresas_categorias_empresa_id_foreign');
            $table->dropForeign('empresas_categorias_categoria_id_foreign');
        });

        Schema::dropIfExists('empresas_categorias');
    }
}
