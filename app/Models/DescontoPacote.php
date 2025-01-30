<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DescontoPacote extends Model
{
    use HasFactory;
    use SoftDeletes;
        
    // Especificando o nome da tabela
    protected $table = 'descontos_pacotes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'user_id',
        'desconto_id',
        'pacote_id',
        'irt',
        'inss',
        'tipo_valor',
        'salario',
        'processamento_id',
        'entidade_id',
    ];

    public function pacote()
    {
        return $this->belongsTo(PacoteSalarial::class, 'pacote_id', 'id');
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
