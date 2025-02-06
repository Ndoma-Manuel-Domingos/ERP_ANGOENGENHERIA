<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DateTime;

class Funcionario extends Model
{
    use HasFactory;
    use SoftDeletes;


    // Especificando o nome da tabela
    protected $table = 'funcionarios';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nif',
        'nome',
        'numero_mecanografico',
        'pais',
        'code',
        'conta',
        'numero_bilhete',
        'local_emissao_bilhete',
        'data_emissao_bilhete',
        'validade_bilhete',
        'numero_passaporte',
        'local_emissao_passaporte',
        'data_emissao_passaporte',
        'validade_passaporte',
        
        'nome_do_pai',
        'nome_da_mae',
        'data_nascimento',
        'genero',
        'estado_civil_id',
        'seguradora_id',
        'provincia_id',
        'municipio_id',
        'distrito_id',
        
        'status',
        'vencimento',
        'gestor_conta',
        'morada',
        'codigo_postal',
        'localidade',
        'telefone',
        'telemovel',
        'email',
        'website',
        'referencia_externa',
        'categoria',
        'user_id',
        'entidade_id',
    ];
    
    public function estado_civil()
    {
        return $this->belongsTo(EstadoCivil::class, 'estado_civil_id', 'id');
    }
    
    public function contrato()
    {
        $user = auth()->user();
        
        return $this->hasOne(Contrato::class, 'funcionario_id', 'id')->where('entidade_id', $user->entidade_id);
    }
        
    public function seguradora()
    {
        return $this->belongsTo(Seguradora::class, 'seguradora_id', 'id');
    }
    
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id', 'id');
    }
    
    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id', 'id');
    }
    
    public function faltas()
    {
        return $this->hasMany(MarcacaoFalta::class, 'funcionario_id', 'id');
    }
    
    public function ferias()
    {
        return $this->hasMany(MarcacaoFeria::class, 'funcionario_id', 'id');
    }
    
    public function ausencias()
    {
        return $this->hasMany(MarcacaoAusencia::class, 'funcionario_id', 'id');
    }
    
    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    public function idade($data)
    {
        $dataAtual = new DateTime();
        $dataNascimento = new DateTime($data);
        $diferenca = $dataNascimento->diff($dataAtual);
        return $diferenca->y;
    }
}
