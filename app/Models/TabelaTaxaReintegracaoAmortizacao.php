<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabelaTaxaReintegracaoAmortizacao extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'tabela_taxas_reintegracoes_amortizacoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'sigla',
    ];
    
    
    public function items()
    {
        return $this->hasMany(TabelaTaxaReintegracaoAmortizacaoItem::class, 'model_id', 'id', 'model_id');
    }


}
