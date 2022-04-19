<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardapiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cardapios', function (Blueprint $table) {
            $table->id();
            $table->string('cardapio', 190);
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('empresa_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cardapios');
    }
}
