<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enfermeiro extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'enfermeiros';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nif',
        'nome',
        'pais',
        
        'nome_do_pai',
        'nome_da_mae',
        'data_nascimento',
        'genero',
        'estado_civil_id',
        'seguradora_id',
        'provincia_id',
        'municipio_id',
        'distrito_id',
        
        'status',
        'morada',
        'codigo_postal',
        'localidade',
        'telefone',
        'telemovel',
        'email',
        'website',
        'referencia_externa',
        'observacao',
        'user_id',
        'entidade_id',
    ];
    
    public function estado_civil()
    {
        return $this->belongsTo(EstadoCivil::class, 'estado_civil_id', 'id');
    }
        
        
    public function seguradora()
    {
        return $this->belongsTo(Seguradora::class, 'seguradora_id', 'id');
    }
    
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id', 'id');
    }
    
    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id', 'id');
    }
    
    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
