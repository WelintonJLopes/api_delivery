<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('data_aceite', $precision = 0)->nullable();
            $table->dateTime('data_entrega', $precision = 0)->nullable();
            $table->dateTime('data_cancelamento', $precision = 0)->nullable();
            $table->text('motivo_cancelamento')->nullable();
            $table->text('observacao')->nullable();
            $table->decimal('valor', $precision = 8, $scale = 2);
            $table->decimal('troco', $precision = 8, $scale = 2);
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('usuario_endereco_id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('recebimento_id');
            $table->unsignedBigInteger('recebimento_cartao_id')->nullable();
            $table->unsignedBigInteger('pedido_status_id');
            $table->unsignedBigInteger('cupom_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
