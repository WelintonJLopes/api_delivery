<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupons', function (Blueprint $table) {
            $table->id();
            $table->string('cupom', 190);
            $table->text('imagem');
            $table->text('descricao');
            $table->decimal('valor', $precision = 8, $scale = 2);
            $table->dateTime('data_expiracao', $precision = 0);
            $table->boolean('status');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');

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
        Schema::table('cupons', function(Blueprint $table){
            $table->dropForeign('cupons_user_id_foreign');
        });

        Schema::dropIfExists('cupons');
    }
}
