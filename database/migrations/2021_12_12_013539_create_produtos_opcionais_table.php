<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosOpcionaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos_opcionais', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('opcional_id');

            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->foreign('opcional_id')->references('id')->on('opcionais');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produtos_opcionais', function(Blueprint $table){
            $table->dropForeign('produtos_opcionais_produto_id_foreign');
            $table->dropForeign('produtos_opcionais_opcional_id_foreign');
        });

        Schema::dropIfExists('produtos_opcionais');
    }
}
