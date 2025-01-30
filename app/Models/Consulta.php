<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consulta extends Model
{
    use HasFactory;
    
    use SoftDeletes;
    // Especificando o nome da tabela
    protected $table = 'consultas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'data_consulta',
        'hora_consulta',
        'paciente_id',
        'consulta_id',
        'medico_id',
        'status',
        'pago',
        'user_id',
        'entidade_id',
        'observacao',
    ];
    
    public function paciente()
    {
        return $this->belongsTo(Cliente::class, 'paciente_id', 'id');
    }
    
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'consulta_id', 'id');
    }
    
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
