<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TurmaFormador extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'turmas_formadores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'user_id',
        'turma_id',
        'formador_id',
        'ano_lectivo_id',
        'entidade_id',
    ];
    
    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }
    
    
    public function formador()
    {
        return $this->belongsTo(Formador::class);
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
