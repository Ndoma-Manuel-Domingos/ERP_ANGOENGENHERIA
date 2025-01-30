<?php

namespace App\Exports;

use App\Models\Produto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProdutoExport implements FromCollection, WithHeadings
{
    public function headings()
    {
        return [
            "referencia",
            // "Codigo",
            // "Nome",
            // "Preço Custo",
            // "Preço",
            // "Preço Venda",
            // "Controlo Stock",
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Produto::select('referencia')->get();
    }
}
