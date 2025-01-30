<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movimento extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subconta_id',
        'status',
        'movimento',
        'credito',
        'debito',
        'exercicio_id',
        'origem',
        'periodo_id',
        'code',
        'observacao',
        'status_caixa',
        'data_at',
        'entidade_id',
    ];

    public function subconta()
    {
        return $this->belongsTo(Subconta::class, 'subconta_id', 'id');
    }

    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class, 'exercicio_id', 'id');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id', 'id');
    }

    public function saldo($credito, $debito)
    {
        if($credito > $debito){
            return $credito - $debito;
        }else if($debito > $credito){
            return $debito - $credito;
        }else {
            return 0;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

}


