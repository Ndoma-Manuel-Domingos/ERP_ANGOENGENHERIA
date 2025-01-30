<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pauta extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'pautas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'aluno_id',
        'turma_id',
        'user_id',
        'prova_1',
        'prova_2',
        'prova_3',
        'status',
        'media',
        'exame',
        'resultado',
        'ano_lectivo_id',
        'entidade_id',
    ];
    
    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }
    
    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
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
