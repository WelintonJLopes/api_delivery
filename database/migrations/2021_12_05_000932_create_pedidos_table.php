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

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('usuario_endereco_id')->references('id')->on('usuarios_enderecos');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('recebimento_id')->references('id')->on('recebimentos');
            $table->foreign('recebimento_cartao_id')->references('id')->on('recebimentos_cartoes');
            $table->foreign('pedido_status_id')->references('id')->on('pedidos_status');
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
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign('pedidos_user_id_foreign');
            $table->dropForeign('pedidos_usuario_endereco_id_foreign');
            $table->dropForeign('pedidos_empresa_id_foreign');
            $table->dropForeign('pedidos_recebimento_id_foreign');
            $table->dropForeign('pedidos_recebimento_cartao_id_foreign');
            $table->dropForeign('pedidos_pedido_status_id_foreign');
            $table->dropForeign('pedidos_cupom_id_foreign');
        });

        Schema::dropIfExists('pedidos');
    }
}
