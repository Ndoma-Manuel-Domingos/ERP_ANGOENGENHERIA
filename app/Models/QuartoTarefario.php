<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuartoTarefario extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'quarto_tarefarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tarefario_id',
        'quarto_id',
        'entidade_id',
        'user_id',
    ];
    
    public function tarefario()
    {
        return $this->belongsTo(Tarefario::class, 'tarefario_id', 'id');
    }
    
    public function quarto()
    {
        return $this->belongsTo(Quarto::class, 'quarto_id', 'id');
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
