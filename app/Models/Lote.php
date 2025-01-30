<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lote extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = "lotes_validade_produtos";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'produto_id',
        'lote',
        'status',
        'codigo_barra',
        'data_validade',
        'entrada',
        'saida',
        'stock_total',
        'entidade_id',
    ];
    
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
    }
}
