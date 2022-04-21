<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecebimentosCartoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recebimentos_cartoes', function (Blueprint $table) {
            $table->id();
            $table->string('administradora', 100);
            $table->string('bandeira', 100);
            $table->timestamps();
            $table->unsignedBigInteger('recebimento_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recebimentos_cartoes');
    }
}
