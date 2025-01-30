<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemFacturaOriginal extends Model
{
    use HasFactory;
    use SoftDeletes;

    
    protected $table = 'items_facturas_originais';

    protected $fillable = [
        'produto_id',
        'factura_id',
        'movimento_id',
        'user_id',
        'quantidade',
        'status',
        'valor_iva',
        'valor_base',
        'valor_pagar',
        'preco_unitario',
        'desconto_aplicado',
        'desconto_aplicado_valor',
        'iva',
        'texto_opcional',
        'code',
        'numero_serie',
        'entidade_id',
        'user_id',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
    }

    // exibir imposto
    public function exibir_imposto_iva($string)
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
