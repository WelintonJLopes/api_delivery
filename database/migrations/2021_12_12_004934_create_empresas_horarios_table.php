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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas_horarios');
    }
}
