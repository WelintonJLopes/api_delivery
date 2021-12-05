<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasCuponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas_cupons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('quantidade');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('cupom_id');

            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('cupom_id')->references('id')->on('cupons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas_cupons', function(Blueprint $table){
            $table->dropForeign('empresas_cupons_empresa_id_foreign');
            $table->dropForeign('empresas_cupons_cupom_id_foreign');
        });

        Schema::dropIfExists('empresas_cupons');
    }
}
