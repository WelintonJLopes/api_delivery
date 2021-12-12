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
            $table->boolean('taxa_entrega');
            $table->decimal('valor_minimo_pedido', $precision = 8, $scale = 2);
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cidade_id');
            $table->unsignedBigInteger('estado_id');
            $table->unsignedBigInteger('especialidade_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cidade_id')->references('id')->on('cidades');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->foreign('especialidade_id')->references('id')->on('especialidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function(Blueprint $table){
            $table->dropForeign('empresas_user_id_foreign');
            $table->dropForeign('empresas_cidade_id_foreign');
            $table->dropForeign('empresas_estado_id_foreign');
            $table->dropForeign('empresas_especialidade_id_foreign');
        });

        Schema::dropIfExists('empresas');
    }
}
