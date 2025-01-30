<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfiguracaoEmpressora extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'empressao',
        'funcionamento',
        'metodo_empressao',
        'entidade_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

}
