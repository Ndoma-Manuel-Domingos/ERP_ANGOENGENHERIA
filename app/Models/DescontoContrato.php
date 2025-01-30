<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DescontoContrato extends Model
{
    use HasFactory;
    use SoftDeletes;
        
    // Especificando o nome da tabela
    protected $table = 'descontos_contratos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'user_id',
        'desconto_id',
        'contrato_id',
        'irt',
        'inss',
        'tipo_valor',
        'tipo',
        'salario',
        'processamento_id',
        'entidade_id',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id', 'id');
    }

    public function desconto()
    {
        return $this->belongsTo(Desconto::class, 'desconto_id', 'id');
    }

    public function processamento()
    {
        return $this->belongsTo(TipoProcessamento::class, 'processamento_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
