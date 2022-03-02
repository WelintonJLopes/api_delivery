<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas_horarios', function (Blueprint $table) {
            $table->id();
            $table->string('dia', 30);
            $table->time('abertura', $precision = 0);
            $table->time('fechamento', $precision = 0);
            $table->time('intervalo', $precision = 0)->nullable();
            $table->time('volta_intervalo', $precision = 0)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas_horarios', function(Blueprint $table){
            $table->dropForeign('empresas_horarios_empresa_id_foreign');
            $table->dropForeign('empresas_horarios_user_id_foreign');
        });

        Schema::dropIfExists('empresas_horarios');
    }
}
