<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloEntidade extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'modulo',
        'descricao',
    ];

    public function tipo_entidade()
    {
        return $this->belongsToMany(TipoEntidade::class);
    }
}
