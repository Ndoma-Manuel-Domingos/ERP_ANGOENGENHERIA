<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxaIRT extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "taxas_irt";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'remuneracao',
        'taxa',
        'abatimento',
        'valor_fixo',
        'excesso',
        'entidade_id',
        'exercicio_id',
    ];

}
