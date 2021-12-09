<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('produto', 190);
            $table->text('imagem');
            $table->text('descricao');
            $table->decimal('valor', $precision = 8, $scale = 2);
            $table->decimal('desconto', $precision = 8, $scale = 2);
            $table->boolean('status');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('empresa_id');

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
        Schema::table('produtos', function(Blueprint $table){
            $table->dropForeign('produtos_user_id_foreign');
            $table->dropForeign('produtos_empresa_id_foreign');
        });

        Schema::dropIfExists('produtos');
    }
}
