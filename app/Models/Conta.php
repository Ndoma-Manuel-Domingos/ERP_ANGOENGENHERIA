<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conta extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'contas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'serie',
        'classe_id',
        'conta',
        'user_id',
        'entidade_id',
    ];

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id', 'id');
    }

    public function subcontas()
    {
        return $this->hasMany(Subconta::class, 'conta_id', 'id')->orderBy('numero', 'asc');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }

}
