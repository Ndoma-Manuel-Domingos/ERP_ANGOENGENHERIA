<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recibo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'recibos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'factura_id',
        'status_venda',
        'status_factura',
        'user_id',
        'caixa_id',
        'data_disponivel',
        'cliente_id',
        'loja_id',
        'valor_entregue',
        'valor_total',
        'valor_pago',
        'data_emissao',
        'data_vencimento',
        'valor_troco',
        'code',
        'pagamento',
        'factura',
        'factura_next',
        'codigo_factura',
        'ano_factura',
        'prazo',
        'desconto',
        'retificado',
        'convertido_factura',
        'factura_divida',
        'anulado',
        'quantidade',

        'total_iva',
        'valor_cash',
        'valor_multicaixa',

        'numeracao_proforma',
        'moeda',
        'total_incidencia',
        'valor_extenso',
        'texto_hash',
        'hash',
        'conta_corrente_cliente',
        'nif_cliente',
        'desconto_percentagem',
        'observacao',
        'referencia',
        'entidade_id',
    ];

    public function items()
    {
        return $this->hasMany(ItemRecibo::class, 'factura_id' ,'id' );
    }

    public function facturas()
    {
        return $this->belongsTo(Venda::class, 'factura_id' ,'id' );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
    public function forma_pagamento($forma)
    {
        $tipoPagamento = [
            'NU' => 'NUMERÁRIO',
            'MB' => 'MULTICAIXA',
            'OU' => 'PAGAMENTO DUPLO',
            'TE' => 'TRANSFERÊNCIA',
            'DE' => 'DEPOSITO',
            // Adicione outros tipos de pagamento conforme necessário
        ];
        
        $pagamento = $forma ?? null;
        
        $tipo = $tipoPagamento[$pagamento] ?? 'PAGAMENTO DUPLO';
        
        return $tipo;
    }
    
        
    function obterCaracteres($texto) {
        $posicoes = [1, 11, 21, 31];
        $caracteres = '';
    
        foreach ($posicoes as $posicao) {
            // Garante que a posição está dentro dos limites da string
            if ($posicao <= strlen($texto)) {
                $caracteres .= $texto[$posicao - 1];
            }
        }
    
        return $caracteres . "-Processado por programa validado Nº 469/AGT/2024 EA-VIEGAS";
    }
       
}
