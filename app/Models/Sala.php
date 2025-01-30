<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sala extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'status',
        'solicitar_ocupacao',
        'entidade_id',
    ];

    
    public function mesas()
    {
        return $this->hasMany(Mesa::class);
    }
}
