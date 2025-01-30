<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Processamento extends Model
{
    use HasFactory;
    use SoftDeletes;
        
    // Especificando o nome da tabela
    protected $table = 'processamentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'data_registro',
        'exercicio_id',
        'periodo_id',
        'funcionario_id',
        'processamento_id',
        'irt',
        'inss',
        'taxa_irt',
        'escalao',
        'inss_empresa',
        'valor_base',
        'valor_iliquido',
        'valor_liquido',
        'faltas',
        'outros_descontos',
        'categoria',
        'dias_processados',
        'total_desconto',
        'total_subsidios',
        'data_inicio',
        'data_final',
        'status',
        'user_id',
        'entidade_id',
    ];

    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class, 'exercicio_id', 'id');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id', 'id');
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id', 'id');
    }

    public function processamento()
    {
        return $this->belongsTo(TipoProcessamento::class, 'processamento_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }

}
