<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 190)->unique();
            $table->string('telefone', 12)->unique()->nullable();
            $table->string('password');
            $table->text('icone')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('sexo', 2)->nullable();
            $table->string('cpf', 15)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('status');
            $table->rememberToken();
            $table->timestamps();
            $table->unsignedBigInteger('grupo_id');
            $table->unsignedBigInteger('cidade_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
