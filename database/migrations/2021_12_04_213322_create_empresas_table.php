<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('empresa', 190);
            $table->text('imagem');
            $table->text('descricao');
            $table->text('endereco');
            $table->string('contato', 12);
            $table->boolean('status');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cidade_id');
            $table->unsignedBigInteger('estado_id');

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::table('empresas', function(Blueprint $table){
            $table->dropForeign('empresas_user_id_foreign');
            $table->dropForeign('empresas_cidade_id_foreign');
            $table->dropForeign('empresas_estado_id_foreign');
        });

        Schema::dropIfExists('empresas');
    }
}
