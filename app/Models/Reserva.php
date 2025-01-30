<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reserva extends Model
{
    use HasFactory;
    use SoftDeletes;
        
    // Especificando o nome da tabela
    protected $table = 'reservas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        
        'valor_unitario',
        'valor_total',
        'valor_pago',
        'valor_divida',
        'valor_troco',
        'valor_retencao_fonte',
        'tarefario_id',
        'total_pessoas',
        'subconta_id',
    
    
        'data_registro',
        'data_inicio',
        'data_final',
        'hora_entrada',
        'hora_saida',
        'status',
        'total_dias',
        'cliente_id',
        'pagamento',
        'quarto_id',
        
        'data_check_in',
        'hora_check_in',
        'data_check_out',
        'hora_check_out',
        'user_check_in',
        'user_check_out',
        'check',
        'code',
        'observacao',
        
        'exercicio_id',
        'periodo_id',
        'data_entrada',
        'data_saida',
        'user_id',
        'entidade_id',
    ];
    
    
    public function subconta()
    {
        return $this->belongsTo(Subconta::class, 'subconta_id', 'id');
    }
    
    public function tarefario()
    {
        return $this->belongsTo(Tarefario::class, 'tarefario_id', 'id');
    }
    
    public function user_ckeck_in()
    {
        return $this->belongsTo(User::class, 'user_check_in', 'id');
    }

    public function user_ckeck_out()
    {
        return $this->belongsTo(User::class, 'user_check_out', 'id');
    }

    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class, 'exercicio_id', 'id');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function quarto()
    {
        return $this->belongsTo(Quarto::class, 'quarto_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }

}
