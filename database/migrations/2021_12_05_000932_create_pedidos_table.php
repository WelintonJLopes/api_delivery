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
            $table->string('status', 50);
            $table->dateTime('data_entrega', $precision = 0)->nullable();
            $table->dateTime('data_pagamento', $precision = 0)->nullable();
            $table->dateTime('data_cancelamento', $precision = 0)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('cupom_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('empresa_id')->references('id')->on('empresas');
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
        Schema::table('pedidos', function(Blueprint $table){
            $table->dropForeign('pedidos_user_id_foreign');
            $table->dropForeign('pedidos_empresa_id_foreign');
            $table->dropForeign('pedidos_cupom_id_foreign');
        });
        
        Schema::dropIfExists('pedidos');
    }
}
