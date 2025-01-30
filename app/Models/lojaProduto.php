<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class lojaProduto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected  $fillable = [
        'produto_id',
        'loja_id',
        'entidade_id',
    ];
    
    public function produtos()
    {
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

}
