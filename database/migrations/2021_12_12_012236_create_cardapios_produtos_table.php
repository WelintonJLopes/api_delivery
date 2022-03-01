<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardapiosProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cardapios_produtos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('destaque');
            $table->unsignedBigInteger('cardapio_id');
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('empresa_id');

            $table->foreign('cardapio_id')->references('id')->on('cardapios');
            $table->foreign('produto_id')->references('id')->on('produtos');
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
        Schema::table('cardapios_produtos', function(Blueprint $table){
            $table->dropForeign('cardapios_produtos_produto_id_foreign');
            $table->dropForeign('cardapios_produtos_cardapio_id_foreign');
            $table->dropForeign('cardapios_produtos_user_id_foreign');
            $table->dropForeign('cardapios_produtos_empresa_id_foreign');
        });

        Schema::dropIfExists('cardapios_produtos');
    }
}
