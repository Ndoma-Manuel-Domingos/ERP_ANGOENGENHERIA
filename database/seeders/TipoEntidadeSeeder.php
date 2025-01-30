<?php

namespace Database\Seeders;

use App\Models\TipoEntidade;
use Illuminate\Database\Seeder;

class TipoEntidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoEntidade::create([ "tipo" => "Restaurante", "descricao" => "Descrição do Restaurante" ]);
        TipoEntidade::create([ "tipo" => "Veterinário", "descricao" => "Descrição do Restaurante"  ]);
        TipoEntidade::create([ "tipo" => "Loja de Roupa", "descricao" => "Descrição da Farmácia" ]);
        TipoEntidade::create([ "tipo" => "Escola de Condução", "descricao" => "Descrição do Escola de Condução" ]);
        TipoEntidade::create([ "tipo" => "Comercio & facturação", "descricao" => "Descrição do Comercio"  ]);
        TipoEntidade::create([ "tipo" => "Hotel", "descricao" => "Descrição do Hotel" ]);
    }
}
