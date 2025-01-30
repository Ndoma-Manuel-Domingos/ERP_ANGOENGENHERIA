<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaFornecedore extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'saldo',
        'divida_corrente',
        'divida_vencida',
        'fornecedor_id',
        'user_id',
        'entidade_id',
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedore::class);
    }
}
