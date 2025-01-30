<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FichaTriagem extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'triagens';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pressao',
        'peso',
        'altura',
        'temperatura',
        'freq_respiratoria',
        'freq_cardiaca',
        'imc',
        'observacoes',
        'user_id',
        'consulta_id',
        'status',
        'entidade_id',
    ];
    
    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'consulta_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
