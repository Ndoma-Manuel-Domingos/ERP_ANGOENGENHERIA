<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matricula extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'matriculas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero',
        'status',
        'codigo',
        'valor_pagamento',
        'user_id',
        'aluno_id',
        'curso_id',
        'turno_id',
        'ano_lectivo_id',
        'sala_id',
        'entidade_id',
    ];
    
    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
    
    public function ano_lectivo()
    {
        return $this->belongsTo(AnoLectivo::class);
    }    
    
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
    
    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }
    
    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
