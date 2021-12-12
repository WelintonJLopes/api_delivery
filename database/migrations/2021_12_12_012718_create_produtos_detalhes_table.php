<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosDetalhesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos_detalhes', function (Blueprint $table) {
            $table->id();
            $table->string('tamanho', 50)->nullable();
            $table->integer('pessoas')->nullable();
            $table->decimal('valor', $precision = 8, $scale = 2);
            $table->decimal('desconto', $precision = 8, $scale = 2);
            $table->boolean('principal');
            $table->timestamps();
            $table->unsignedBigInteger('produto_id');

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
        Schema::table('produtos_detalhes', function(Blueprint $table){
            $table->dropForeign('produtos_detalhes_produto_id_foreign');
        });

        Schema::dropIfExists('produtos_detalhes');
    }
}
