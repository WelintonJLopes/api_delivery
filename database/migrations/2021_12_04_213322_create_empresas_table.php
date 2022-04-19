<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('empresa', 190);
            $table->string('cnpj', 15)->unique()->nullable();
            $table->text('imagem');
            $table->text('sobre');
            $table->string('telefone', 12);
            $table->string('email', 190)->unique()->nullable();
            $table->string('rua', 190);
            $table->integer('numero');
            $table->string('bairro', 190);
            $table->string('complemento', 190);
            $table->integer('cep');
            $table->boolean('status');
            $table->boolean('status_funcionamento');
            $table->boolean('entrega');
            $table->decimal('taxa_entrega', $precision = 8, $scale = 2);
            $table->decimal('valor_minimo_pedido', $precision = 8, $scale = 2);
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cidade_id');
            $table->unsignedBigInteger('estado_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
