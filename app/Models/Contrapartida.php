<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contrapartida extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'contrapartidas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subconta_id',
        'tipo_credito_id',
        'user_id',
        'entidade_id',
    ];

    public function subconta()
    {
        return $this->belongsTo(Subconta::class, 'subconta_id', 'id');
    }

    public function tipo_credito()
    {
        return $this->belongsTo(TipoCredito::class, 'tipo_credito_id', 'id');
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
