<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosProdutosOpcionaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_produtos_opcionais', function (Blueprint $table) {
            $table->id();
            $table->integer('quantidade');
            $table->decimal('valor', $precision = 8, $scale = 2);
            $table->timestamps();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('opcional_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('empresa_id');

            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->foreign('opcional_id')->references('id')->on('opcionais');
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
        Schema::table('pedidos_produtos_opcionais', function(Blueprint $table){
            $table->dropForeign('pedidos_produtos_opcionais_pedido_id_foreign');
            $table->dropForeign('pedidos_produtos_opcionais_produto_id_foreign');
            $table->dropForeign('pedidos_produtos_opcionais_opcional_id_foreign');
            $table->dropForeign('pedidos_produtos_opcionais_user_id_foreign');
            $table->dropForeign('pedidos_produtos_opcionais_empresa_id_foreign');
        });

        Schema::dropIfExists('pedidos_produtos_opcionais');
    }
}
