<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contrato extends Model
{
    use HasFactory;
    use SoftDeletes;
        
    // Especificando o nome da tabela
    protected $table = 'contratos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'user_id',
        'funcionario_id',
        'salario_base',
        'categoria_id',
        'renovacoes_efectuadas',
        'situacao_apos_renovacao',
        'duracao_renovacao',
        'antiguidade',
        'duracao_renovacao',
        'motivo_id',
        'cargo_id',
        'tipo_contrato_id',
        'hora_entrada',
        'hora_saida',
        'data_inicio',
        'data_final',
        'data_envio_previo',
        'data_demissao',
        'forma_pagamento_id',
        'pacote_salarial_id',
        'dias_processamento',
        'subsidio_natal',
        'forma_pagamento_natal',
        'mes_pagamento_natal',
        'subsidio_ferias',
        'forma_pagamento_ferias',
        'mes_pagamento_ferias',
        
        'entidade_id',
    ];

    public function motivo()
    {
        return $this->belongsTo(MotivoSaida::class, 'motivo_id', 'id');
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id', 'id');
    }

    public function forma_pagamento()
    {
        return $this->belongsTo(TipoPagamento::class, 'forma_pagamento_id', 'id');
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaCargo::class, 'categoria_id', 'id');
    }

    public function pacote_salarial()
    {
        return $this->belongsTo(PacoteSalarial::class, 'pacote_salarial_id', 'id');
    }
    
    public function subsidios_contrato()
    {
        return $this->hasMany(SubsidioContrato::class, 'contrato_id', 'id');
    }
    
    public function descontos_contrato()
    {
        return $this->hasMany(DescontoContrato::class, 'contrato_id', 'id');
    }
        
    public function subsidios_pacotes()
    {
        return $this->hasMany(SubsidioPacote::class, 'pacote_id', 'id');
    }
    
    public function desconto_pacotes()
    {
        return $this->hasMany(DescontoPacote::class, 'pacote_id', 'id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id', 'id');
    }

    public function tipo_contrato()
    {
        return $this->belongsTo(TipoContrato::class, 'tipo_contrato_id', 'id');
    }
   

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
         
    public function forma_pagamento_subcidio($string)
    {
        switch($string){
            case 'completa': 
                return "Completa Mês Subsídio";
                break;
            case 'partes': 
                return "Duodécimo";
                break;
        }
    }
         
    public function dias_processamentos($string)
    {

        switch($string){
            case 'dias_uteis_variaveis': 
                return "Dias Úteis Variáveis";
                break;
            case 'dias_fixo': 
                return "Dias Fixos (30)";
                break;
            case 'dias_uteis_fixo': 
                return "Dias Úteis Fixos";
                break;
        }
    }
     
    public function descricao_mes($string)
    {
        switch($string){
            case 1: 
                return "Janeiro";
                break;
            case 2: 
                return "Fevereiro";
                break;
            case 3: 
                return "Março";
                break;
            case 4: 
                return "Abril";
                break;
            case 5: 
                return "Maio";
                break;
            case 6: 
                return "Junho";
                break;
            case 7: 
                return "Julho";
                break;
            case 8: 
                return "Agosto";
                break;
            case 9: 
                return "Setembro";
                break;
            case 10: 
                return "Outubro";
                break;
            case 11: 
                return "Novembro";
                break;
            case 12: 
                return "Dezembro";
                break;
        }
    }

}
