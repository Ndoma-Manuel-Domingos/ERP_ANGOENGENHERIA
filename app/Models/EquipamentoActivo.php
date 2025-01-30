<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipamentoActivo extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'equipamentos_activos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'numero_serie',
        'codigo_barra',
        'anexo',
        'fornecedor_id',
        'numero_factura',
        'descricao',
        'code',
        'total',
        'quantidade',
        'classificacao_id',
        'conta_id',
        'staus_financeiro',
        'base_incidencia',
        'iva',
        'iva_total',
        'desconto',
        'valor_desconto',
        'iva_dedutivel',
        'iva_d',
        'iva_n_dedutivel',
        'iva_nd',
        'custo_aquisicao',
        'valor_contabilistico',
        'data_aquisicao',
        'data_utilizacao',
        'data_att',
        'user_id',
        'entidade_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'entidade_id', 'id');
    }
    
    public function classificacao()
    {
        return $this->belongsTo(TabelaTaxaReintegracaoAmortizacaoItem::class, 'classificacao_id', 'id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedore::class, 'fornecedor_id', 'id');
    }
    
    public function conta()
    {
        return $this->belongsTo(Subconta::class, 'conta_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }

}
