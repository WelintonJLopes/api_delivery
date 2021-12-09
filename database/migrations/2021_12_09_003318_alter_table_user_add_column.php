<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUserAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table){
            $table->unsignedBigInteger('cidade_id');
            $table->unsignedBigInteger('estado_id');
            $table->unsignedBigInteger('grupo_id');

            $table->foreign('cidade_id')->references('id')->on('cidades');
            $table->foreign('estado_id')->references('id')->on('estados');
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
        Schema::table('users', function(Blueprint $table){
            $table->dropForeign('users_cidade_id_foreign');
            $table->dropForeign('users_estado_id_foreign');
            $table->dropForeign('users_grupo_id_foreign');

            $table->dropColumn('cidade_id');
            $table->dropColumn('estado_id');
            $table->dropColumn('grupo_id');
        });
    }
}
