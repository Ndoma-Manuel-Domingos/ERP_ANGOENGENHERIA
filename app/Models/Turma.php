<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turma extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'turmas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'preco',
        'user_id',
        'ano_lectivo_id',
        'curso_id',
        'turno_id',
        'sala_id',
        'entidade_id',
    ];
        
    public function alunos()
    {
        return $this->hasMany(TurmaAluno::class, 'turma_id', 'id');
    }
        
    public function formadores()
    {
        return $this->hasMany(TurmaFormador::class, 'turma_id', 'id');
    }
    
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
    
    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }
    
    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }
    
    public function ano_lectivo()
    {
        return $this->belongsTo(AnoLectivo::class);
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
