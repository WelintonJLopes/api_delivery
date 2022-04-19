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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cardapios_produtos');
    }
}
