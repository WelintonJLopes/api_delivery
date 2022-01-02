<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas_entregas', function (Blueprint $table) {
            $table->id();
            $table->decimal('taxa_entrega', $precision = 8, $scale = 2);
            $table->timestamps();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('cidade_id');
            $table->unsignedBigInteger('estado_id');

            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('cidade_id')->references('id')->on('cidades');
            $table->foreign('estado_id')->references('id')->on('estados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas_entregas', function(Blueprint $table){
            $table->dropForeign('empresas_entregas_empresa_id_foreign');
            $table->dropForeign('empresas_entregas_cidade_id_foreign');
            $table->dropForeign('empresas_entregas_estado_id_foreign');
        });

        Schema::dropIfExists('empresas_entregas');
    }
}
