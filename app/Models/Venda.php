<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venda extends Model
{
    use HasFactory;
    use SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'status_venda',
        'status_factura',
        'user_id',
        'caixa_id',
        'mesa_id',
        'quarto_id',
        'banco_id',
        'mesa_caixa',
        'data_disponivel',
        'cliente_id',
        'loja_id',
        'valor_entregue',
        'valor_total',
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
        'valor_divida',
        'valor_pago',
        'anulado',
        'quantidade',
        'conta_movimento',

        'total_iva',
        'valor_cash',
        'valor_multicaixa',
        
        'nome_cliente',
        'documento_nif',
        
        'contas',

        'numeracao_proforma',
        'moeda',
        'total_incidencia',
        'total_retencao_fonte',
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
        return $this->hasMany(Itens_venda::class, 'factura_id' ,'id' );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function caixa()
    {
        return $this->belongsTo(Caixa::class, 'caixa_id', 'id');
    }
    
    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'mesa_id', 'id');
    }
    
    public function quarto()
    {
        return $this->belongsTo(Quarto::class, 'quarto_id', 'id');
    }


    public function banco()
    {
        return $this->belongsTo(ContaBancaria::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // exibir imposto
    public function exibir_factura($string)
    {
        if ($string == "FR"){
            return "Factua / Recibo";
        }else if($string == "FT"){
            return "Factura";
        }else if ($string == "RG"){
            return "Factura / Global";
        }else if ($string == "OT"){
            return "Orçamento";
        }else if ($string == "EC"){
            return "Ecomenda";
        }else if ($string == "PP"){
            return "Factura / Pró-Forma";
        }
    }
    // exibir imposto
    public function exibir_nome_factura($factura, $ano, $numero)
    {
        $codigo_designacao_factura = ENV('DESIGNACAO_FACTURA');
        
        return "{$factura} {$codigo_designacao_factura}---{$ano}/{$numero}";
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
    
        return $caracteres . "-Processado por programa validado Nº º 469/AGT/2024 EA-VIEGAS";
    }
       
}
