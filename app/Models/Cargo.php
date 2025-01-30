<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use HasFactory;
    use SoftDeletes;
        
    // Especificando o nome da tabela
    protected $table = 'cargos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'user_id',
        'departamento_id',
        'salario_base',
        'entidade_id',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produto()
    {
        return $this->hasOne(Produto::class);
    }

}
