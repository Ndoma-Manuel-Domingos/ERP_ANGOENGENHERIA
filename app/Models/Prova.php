<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prova extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'provas';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'data_at',
        'nota_maxima',
        'modulo_id',
        'turma_id',
        'formador_id',
        'user_id',
        'entidade_id',
    ];
    
    public function questoes()
    {
        return $this->hasMany(ProvaQuestao::class, 'prova_id', 'id');
    }
    
    public function modulo()
    {
        return $this->belongsTo(CursoModulo::class, 'modulo_id', 'id');
    }
    
    public function formador()
    {
        return $this->belongsTo(Formador::class);
    }
    
    public function turma()
    {
        return $this->belongsTo(Turma::class);
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
