<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DadoEmpresa extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'morada',
        'codigo_postal',
        'cidade',
        'conservatoria',
        'capital_social',
        'nome_comercial',
        'slogan',
        'logotipo',
        'pais',
        'moeda',
        'taxa_iva',
        'motivo_isencao',
        'telefone',
        'website',
        'user_id',
        'entidade_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // exibir imposto
    public function exibir_imposto($string)
    {
        if ($string == ""){
            return 0;
        }else if($string == "ISE"){
            return 0;
        }else if ($string == "RED"){
            return 2;
        }else if ($string == "INT"){
            return 5;
        }else if ($string == "OUT"){
            return 7;
        }else if ($string == "NOR"){
            return 14;
        }
    }
}
