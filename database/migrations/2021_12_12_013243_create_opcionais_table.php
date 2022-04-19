<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcionaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opcionais', function (Blueprint $table) {
            $table->id();
            $table->string('opcional', 100);
            $table->text('descricao')->nullable();
            $table->decimal('valor', $precision = 8, $scale = 2);
            $table->boolean('status');
            $table->integer('minimo');
            $table->integer('maximo');
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
        Schema::dropIfExists('opcionais');
    }
}
