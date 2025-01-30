<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Permission::create(['nome' => "Vendas"]);
       Permission::create(['nome' => "Gestão de Clientes"]);
       Permission::create(['nome' => "Configuração"]);
       Permission::create(['nome' => "Gestão de Utilizadores"]);
       Permission::create(['nome' => "Autoridade Tributária"]);
       Permission::create(['nome' => "Gestão de Produtos"]);
       Permission::create(['nome' => "Relatórios"]);
       Permission::create(['nome' => "Mudar de Caixa/Loja"]);
    }
}
