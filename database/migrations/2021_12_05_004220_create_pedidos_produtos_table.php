<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_produtos', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor', $precision = 8, $scale = 2);
            $table->integer('quantidade');
            $table->timestamps();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('produto_id');

            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->foreign('produto_id')->references('id')->on('produtos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos_produtos', function(Blueprint $table){
            $table->dropForeign('pedidos_produtos_pedido_id_foreign');
            $table->dropForeign('pedidos_produtos_produto_id_foreign');
        });

        Schema::dropIfExists('pedidos_produtos');
    }
}