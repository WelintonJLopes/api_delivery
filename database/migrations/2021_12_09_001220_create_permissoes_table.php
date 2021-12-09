<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissoes', function (Blueprint $table) {
            $table->id();
            $table->string('permissao', 190)->unique();
            $table->text('descricao');
            $table->timestamps();
            $table->unsignedBigInteger('grupo_id');

            $table->foreign('grupo_id')->references('id')->on('grupos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissoes', function(Blueprint $table){
            $table->dropForeign('permissoes_grupo_id_foreign');
        });

        Schema::dropIfExists('permissoes');
    }
}
