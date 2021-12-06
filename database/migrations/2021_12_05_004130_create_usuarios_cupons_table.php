<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosCuponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios_cupons', function (Blueprint $table) {
            $table->id();
            $table->boolean('utilizado');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cupom_id');

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::table('usuarios_cupons', function(Blueprint $table){
            $table->dropForeign('usuarios_cupons_user_id_foreign');
            $table->dropForeign('usuarios_cupons_cupom_id_foreign');
        });

        Schema::dropIfExists('usuarios_cupons');
    }
}
