<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formador extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'formadores';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nif',
        'nome',
        'pai',
        'mae',
        'data_nascimento',
        'genero',
        'estado_civil',
        'pais',
        'status',
        'id_user',
        'morada',
        'codigo_postal',
        'localidade',
        'telefone',
        'telemovel',
        'email',
        'website',
        'observacao',
        'user_id',
        'entidade_id',
    ];
    
    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
