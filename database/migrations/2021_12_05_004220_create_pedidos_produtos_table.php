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
            $table->decimal('desconto', $precision = 8, $scale = 2);
            $table->integer('quantidade');
            $table->string('observacao', 190)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('produto_detalhe_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos_produtos');
    }
}
