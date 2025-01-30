<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'is_admin',
        'type_user',
        'login_access',
        'level',
        'status',
        'password',
        'entidade_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function empresa()
    {
        return $this->hasOne(Entidade::class, 'id', 'entidade_id');
    }
    
    public function formador()
    {
        return $this->belongsTo(Formador::class, 'id', 'id_user');
    }
    
    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'id', 'id_user');
    }
    
    public function company()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
    
    public function configuracao_empressao()
    {
        return $this->hasOne(ConfiguracaoEmpressora::class, 'id', 'entidade_id');
    }

    public function marcas()
    {
        return $this->hasMany(Marca::class);
    }

    public function variacoes()
    {
        return $this->hasMany(Variacao::class);
    }

    public function categorias()
    {
        return $this->hasMany(Categoria::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function estoques()
    {
        return $this->hasMany(Estoque::class);
    }

}
