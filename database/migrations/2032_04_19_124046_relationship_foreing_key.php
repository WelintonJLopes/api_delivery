<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RelationshipForeingKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('grupo_id')->references('id')->on('grupos');
            $table->foreign('cidade_id')->references('id')->on('cidades');
        });

        Schema::table('empresas', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cidade_id')->references('id')->on('cidades');
            $table->foreign('estado_id')->references('id')->on('estados');
        });

        Schema::table('cupons', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('usuario_endereco_id')->references('id')->on('usuarios_enderecos');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('recebimento_id')->references('id')->on('recebimentos');
            $table->foreign('recebimento_cartao_id')->references('id')->on('recebimentos_cartoes');
            $table->foreign('pedido_status_id')->references('id')->on('pedidos_status');
            $table->foreign('cupom_id')->references('id')->on('cupons');
        });

        Schema::table('produtos', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('categoria_id')->references('id')->on('categoria');
        });

        Schema::table('logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('usuarios_cupons', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cupom_id')->references('id')->on('cupons');
        });

        Schema::table('empresas_cupons', function (Blueprint $table) {
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('cupom_id')->references('id')->on('cupons');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('pedidos_produtos', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->foreign('produto_detalhe_id')->references('id')->on('produtos_detalhes');
        });

        Schema::table('permissoes', function (Blueprint $table) {
            $table->foreign('grupo_id')->references('id')->on('grupos');
        });

        Schema::table('usuarios_enderecos', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cidade_id')->references('id')->on('cidades');
            $table->foreign('estado_id')->references('id')->on('estados');
        });

        Schema::table('empresas_horarios', function (Blueprint $table) {
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('empresas_recebimentos', function (Blueprint $table) {
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('recebimento_id')->references('id')->on('recebimentos');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('recebimentos_cartoes', function (Blueprint $table) {
            $table->foreign('recebimento_id')->references('id')->on('recebimentos');
        });

        Schema::table('empresas_entregas', function (Blueprint $table) {
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('cidade_id')->references('id')->on('cidades');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('cardapios', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });

        Schema::table('cardapios_produtos', function (Blueprint $table) {
            $table->foreign('cardapio_id')->references('id')->on('cardapios');
            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });

        Schema::table('produtos_detalhes', function (Blueprint $table) {
            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });

        Schema::table('opcionais', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });

        Schema::table('produtos_opcionais', function (Blueprint $table) {
            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->foreign('opcional_id')->references('id')->on('opcionais');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });

        Schema::table('empresas_categorias', function (Blueprint $table) {
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('pedidos_produtos_opcionais', function (Blueprint $table) {
            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->foreign('pedido_produto_id')->references('id')->on('pedidos_produtos');
            $table->foreign('opcional_id')->references('id')->on('opcionais');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_grupo_id_foreign');
            $table->dropForeign('users_cidade_id_foreign');
        });

        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign('empresas_user_id_foreign');
            $table->dropForeign('empresas_cidade_id_foreign');
            $table->dropForeign('empresas_estado_id_foreign');
        });

        Schema::table('cupons', function (Blueprint $table) {
            $table->dropForeign('cupons_user_id_foreign');
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign('pedidos_user_id_foreign');
            $table->dropForeign('pedidos_usuario_endereco_id_foreign');
            $table->dropForeign('pedidos_empresa_id_foreign');
            $table->dropForeign('pedidos_recebimento_id_foreign');
            $table->dropForeign('pedidos_recebimento_cartao_id_foreign');
            $table->dropForeign('pedidos_pedido_status_id_foreign');
            $table->dropForeign('pedidos_cupom_id_foreign');
        });

        Schema::table('produtos', function (Blueprint $table) {
            $table->dropForeign('produtos_user_id_foreign');
            $table->dropForeign('produtos_empresa_id_foreign');
            $table->dropForeign('produtos_categoria_id_foreign');
        });

        Schema::table('logs', function (Blueprint $table) {
            $table->dropForeign('logs_user_id_foreign');
        });

        Schema::table('usuarios_cupons', function (Blueprint $table) {
            $table->dropForeign('usuarios_cupons_user_id_foreign');
            $table->dropForeign('usuarios_cupons_cupom_id_foreign');
        });

        Schema::table('empresas_cupons', function (Blueprint $table) {
            $table->dropForeign('empresas_cupons_empresa_id_foreign');
            $table->dropForeign('empresas_cupons_cupom_id_foreign');
            $table->dropForeign('empresas_cupons_user_id_foreign');
        });

        Schema::table('pedidos_produtos', function (Blueprint $table) {
            $table->dropForeign('pedidos_produtos_user_id_foreign');
            $table->dropForeign('pedidos_produtos_empresa_id_foreign');
            $table->dropForeign('pedidos_produtos_pedido_id_foreign');
            $table->dropForeign('pedidos_produtos_produto_id_foreign');
            $table->dropForeign('pedidos_produtos_produto_detalhe_id_foreign');
        });

        Schema::table('permissoes', function (Blueprint $table) {
            $table->dropForeign('permissoes_grupo_id_foreign');
        });

        Schema::table('usuarios_enderecos', function (Blueprint $table) {
            $table->dropForeign('usuarios_enderecos_user_id_foreign');
            $table->dropForeign('usuarios_enderecos_cidade_id_foreign');
            $table->dropForeign('usuarios_enderecos_estado_id_foreign');
        });

        Schema::table('empresas_horarios', function (Blueprint $table) {
            $table->dropForeign('empresas_horarios_empresa_id_foreign');
            $table->dropForeign('empresas_horarios_user_id_foreign');
        });

        Schema::table('empresas_recebimentos', function (Blueprint $table) {
            $table->dropForeign('empresas_recebimentos_empresa_id_foreign');
            $table->dropForeign('empresas_recebimentos_recebimento_id_foreign');
            $table->dropForeign('empresas_recebimentos_user_id_foreign');
        });

        Schema::table('recebimentos_cartoes', function (Blueprint $table) {
            $table->dropForeign('recebimentos_cartoes_recebimento_id_foreign');
        });

        Schema::table('empresas_entregas', function (Blueprint $table) {
            $table->dropForeign('empresas_entregas_empresa_id_foreign');
            $table->dropForeign('empresas_entregas_cidade_id_foreign');
            $table->dropForeign('empresas_entregas_estado_id_foreign');
            $table->dropForeign('empresas_entregas_user_id_foreign');
        });

        Schema::table('cardapios', function (Blueprint $table) {
            $table->dropForeign('cardapios_user_id_foreign');
            $table->dropForeign('cardapios_empresa_id_foreign');
        });

        Schema::table('cardapios_produtos', function (Blueprint $table) {
            $table->dropForeign('cardapios_produtos_produto_id_foreign');
            $table->dropForeign('cardapios_produtos_cardapio_id_foreign');
            $table->dropForeign('cardapios_produtos_user_id_foreign');
            $table->dropForeign('cardapios_produtos_empresa_id_foreign');
        });

        Schema::table('produtos_detalhes', function (Blueprint $table) {
            $table->dropForeign('produtos_detalhes_produto_id_foreign');
            $table->dropForeign('produtos_detalhes_user_id_foreign');
            $table->dropForeign('produtos_detalhes_empresa_id_foreign');
        });

        Schema::table('opcionais', function (Blueprint $table) {
            $table->dropForeign('opcionais_user_id_foreign');
            $table->dropForeign('opcionais_empresa_id_foreign');
        });

        Schema::table('produtos_opcionais', function (Blueprint $table) {
            $table->dropForeign('produtos_opcionais_produto_id_foreign');
            $table->dropForeign('produtos_opcionais_opcional_id_foreign');
            $table->dropForeign('produtos_opcionais_user_id_foreign');
            $table->dropForeign('produtos_opcionais_empresa_id_foreign');
        });

        Schema::table('empresas_categorias', function (Blueprint $table) {
            $table->dropForeign('empresas_categorias_empresa_id_foreign');
            $table->dropForeign('empresas_categorias_categoria_id_foreign');
            $table->dropForeign('empresas_categorias_user_id_foreign');
        });

        Schema::table('pedidos_produtos_opcionais', function (Blueprint $table) {
            $table->dropForeign('pedidos_produtos_opcionais_pedido_id_foreign');
            $table->dropForeign('pedidos_produtos_opcionais_produto_id_foreign');
            $table->dropForeign('pedidos_produtos_opcionais_pedido_produto_id_foreign');
            $table->dropForeign('pedidos_produtos_opcionais_opcional_id_foreign');
            $table->dropForeign('pedidos_produtos_opcionais_user_id_foreign');
            $table->dropForeign('pedidos_produtos_opcionais_empresa_id_foreign');
        });
    }
}
