<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistroCompraProduto extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'registros_compras_produtos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'produto_id',
        'valor_pago',
        'quantidade',
        'user_id',
        'entidade_id',
    ];

    public function produto()
    {
        return $this->belongsTo(ProdutoCompra::class, 'produto_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'entidade_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }

}
