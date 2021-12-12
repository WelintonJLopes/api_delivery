<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosEnderecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios_enderecos', function (Blueprint $table) {
            $table->id();
            $table->string('apelido', 80);
            $table->string('rua', 190);
            $table->integer('numero');
            $table->string('bairro', 190);
            $table->string('complemento', 190);
            $table->integer('cep');
            $table->boolean('principal');
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
        Schema::table('usuarios_enderecos', function(Blueprint $table){
            $table->dropForeign('usuarios_enderecos_user_id_foreign');
            $table->dropForeign('usuarios_enderecos_cidade_id_foreign');
            $table->dropForeign('usuarios_enderecos_estado_id_foreign');
        });

        Schema::dropIfExists('usuarios_enderecos');
    }
}
