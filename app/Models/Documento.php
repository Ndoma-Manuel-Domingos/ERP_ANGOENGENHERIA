<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documento extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'documentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipo_documento_id',
        'status',
        'aluno_id',
        'descricao',
        'user_id',
        'entidade_id',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'entidade_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }

}
