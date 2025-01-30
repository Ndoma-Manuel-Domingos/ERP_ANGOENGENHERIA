<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'categoria',
        'status',
        'user_id',
        'entidade_id',
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
