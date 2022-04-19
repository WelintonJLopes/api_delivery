<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasCuponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas_cupons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('quantidade');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('cupom_id');
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
        Schema::dropIfExists('empresas_cupons');
    }
}
