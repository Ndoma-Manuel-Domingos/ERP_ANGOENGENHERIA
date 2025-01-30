<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrador = Role::create(['nome' => "Administrador"]);
        $gerente = Role::create(['nome' => "Gerente de Loja"]);
        $operador = Role::create(['nome' => "Operador de Caixa"]);
        $contabilista = Role::create(['nome' => "Contabilista"]);
        $basico = Role::create(['nome' => "Básico"]);

        $whereVendas = Permission::where(['nome' => "Vendas"])->first();
        $whereCliente = Permission::where(['nome' => "Gestão de Clientes"])->first();
        $whereConfiguracao = Permission::where(['nome' => "Configuração"])->first();
        $whereUtilizadores = Permission::where(['nome' => "Gestão de Utilizadores"])->first();
        $whereTributaria = Permission::where(['nome' => "Autoridade Tributária"])->first();
        $whereProdutos = Permission::where(['nome' => "Gestão de Produtos"])->first();
        $whereRelatorio = Permission::where(['nome' => "Relatórios"])->first();
        $whereCaixaLoja = Permission::where(['nome' => "Mudar de Caixa/Loja"])->first();

        // atribuir permissóes Administrador
        $administrador->permissions()->attach($whereVendas);
        $administrador->permissions()->attach($whereCliente);
        $administrador->permissions()->attach($whereConfiguracao);
        $administrador->permissions()->attach($whereUtilizadores);
        $administrador->permissions()->attach($whereTributaria);
        $administrador->permissions()->attach($whereProdutos);
        $administrador->permissions()->attach($whereRelatorio);
        $administrador->permissions()->attach($whereCaixaLoja);

        // atribuir permissóes generente de loja
        $gerente->permissions()->attach($whereVendas);
        $gerente->permissions()->attach($whereCliente);
        $gerente->permissions()->attach($whereProdutos);
        $gerente->permissions()->attach($whereRelatorio);
        $gerente->permissions()->attach($whereCaixaLoja);

        // atribuir permissoes operador de caixa
        $operador->permissions()->attach($whereVendas);
        $operador->permissions()->attach($whereCliente);

        
        // atribuir permissoes Contabilista
        $contabilista->permissions()->attach($whereTributaria);
        $contabilista->permissions()->attach($whereCliente);
        $contabilista->permissions()->attach($whereRelatorio);

        // atribuir permissoes Básico
        $basico->permissions()->attach($whereVendas);
    }
}
