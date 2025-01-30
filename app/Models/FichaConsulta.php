<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FichaConsulta extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'ficha_consultas';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'queixa_principal',
        'hipotese_diagnostico',
        'diagnostico',
        'receita',
        'observacoes',
        'historia_doenca_actual',
        'exame_fisico',
        'triagem_id',
        'consulta_id',
        'user_id',
        'status',
        'entidade_id',
    ];
    
    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'consulta_id', 'id');
    }
    
    public function triagem()
    {
        return $this->belongsTo(FichaTriagem::class, 'triagem_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
