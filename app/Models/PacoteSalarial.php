<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PacoteSalarial extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'pacotes-salarial';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cargo_id',
        'categoria_id',
        'salario_base',
        'status',
        'user_id',
        'entidade_id',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaCargo::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function subsidios_pacotes()
    {
        return $this->hasMany(SubsidioPacote::class, 'pacote_id', 'id');
    }
    
    public function desconto_pacotes()
    {
        return $this->hasMany(DescontoPacote::class, 'pacote_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

}
