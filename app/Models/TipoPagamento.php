<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoPagamento extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'titulo',
        'troco',
        'status',
        'tipo',
    ];


    public function booleano($boolean)
    {
        if($boolean == true){
            return "Sim";
        }else if($boolean == false){
            return "Não";
        }
    }

    public function tipo($string)
    {
        if($string == "NU"){
            return "Numerário";
        }else if($string == "NU"){
            return "";
        }else if($string == "CC"){
            return "Cartão de Crédito";
        }else if($string == "CD"){
            return "Cartão de Débito";
        }else if($string == "CO"){
            return "Cartão Oferta";
        }else if($string == "CS"){
            return "Compensação de Saldos C/Cs";
        }else if($string == "DE"){
            return "Cartão de Pontos";
        }else if($string == "TR"){
            return "Ticket Restaurante";
        }else if($string == "MB"){
            return "Referência MB";
        }else if($string == "OU"){
            return "Outro";
        }else if($string == "CH"){
            return "Cheque Bancário";
        }else if($string == "LC"){
            return "Letra Comercial";
        }else if($string == "TB"){
            return "Transferência Bancária";
        }else if($string == "PR"){
            return "Permuta de Bens";
        }else if($string == "DNP"){
            return "Pagamento em conta corrente - entre 15 e 90 dias ou numa data específica";
        }
    }
}
