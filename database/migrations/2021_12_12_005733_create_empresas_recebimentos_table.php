<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasRecebimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas_recebimentos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('recebimento_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('recebimento_id')->references('id')->on('recebimentos');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas_recebimentos', function(Blueprint $table){
            $table->dropForeign('empresas_recebimentos_empresa_id_foreign');
            $table->dropForeign('empresas_recebimentos_recebimento_id_foreign');
            $table->dropForeign('empresas_recebimentos_user_id_foreign');
        });

        Schema::dropIfExists('empresas_recebimentos');
    }
}
