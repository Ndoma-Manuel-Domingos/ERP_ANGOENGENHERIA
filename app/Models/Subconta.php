<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subconta extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'subcontas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'numero',
        'tipo_conta',
        'code',
        'conta_id',
        'tipo_operacao',
        'user_id',
        'entidade_id',
    ];

    public function conta()
    {
        return $this->belongsTo(Conta::class, 'conta_id', 'id');
    }

    public function movimentos()
    {
        return $this->hasMany(Movimento::class, 'subconta_id', 'id');
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
