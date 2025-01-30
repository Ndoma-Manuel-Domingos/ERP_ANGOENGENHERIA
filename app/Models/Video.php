<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'videos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'data_at',
        'arquivo',
        'turma_id',
        'modulo_id',
        'type',
        'formador_id',
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
