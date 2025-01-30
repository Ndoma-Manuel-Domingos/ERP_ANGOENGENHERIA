<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProvaQuestao extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'provas_questoes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prova_id',
        'questao',
        
        'opcao_a',
        'opcao_b',
        'opcao_c',
        'opcao_d',
        'opcao_e',
        'nota',
        'opcao_certa',
        
        'user_id',
        'entidade_id',
    ];
    
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
