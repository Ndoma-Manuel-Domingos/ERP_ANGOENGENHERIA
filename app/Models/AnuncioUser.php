<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnuncioUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'anuncios_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model_id',
        'anuncio_id',
        'status',
        'type',
        'user_id',
        'entidade_id',
    ];

    public function anuncio()
    {
        return $this->belongsTo(Anuncio::class);
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'model_id', 'id')->where('type', 'aluno');
    }

    public function formador()
    {
        return $this->belongsTo(Formador::class, 'model_id', 'id')->where('type', 'formador');
    }

    public function outro()
    {
        return $this->belongsTo(User::class, 'model_id', 'id')->where('type', 'outro');
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
