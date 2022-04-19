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
            $table->unsignedBigInteger('pedido_produto_id');
            $table->unsignedBigInteger('opcional_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('empresa_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos_produtos_opcionais');
    }
}
