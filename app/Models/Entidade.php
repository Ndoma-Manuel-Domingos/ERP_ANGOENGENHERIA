<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entidade extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nif',
        'nome',
        'tipo_id',
        'status',
        'tipo_empresa',
        'morada',
        'tipo_factura',
        'codigo_postal',
        'cidade',
        'conservatoria',
        'capital_social',
        'nome_comercial',
        'slogan',
        'logotipo',
        'pais',
        'moeda',
        'taxa_iva',
        'motivo_isencao',
        'telefone',
        'telemovel',
        'tipo_inventario',
        'email',
        'fax',
        'website',
        'level',
        'banco',
        'conta',
        'iban',
        'banco1',
        'conta1',
        'iban1',
        'inicializacao',
		'nome_cliente',
		'documento_nif',
        'tipo_regime_id',
        'imposto_id',
        'motivo_id',
        'finalizacao',
        'cabecalho',
        'rodape',
        'finalizacao_venda',
        'promocoes_email',
        'novidade_email',
        'marca_d_agua_facturas',
        'sigla_factura',
        'ano_factura',
        'taxa_retencao_fonte',
    ];

    public function taxa_imposto()
    {
        return $this->belongsTo(Imposto::class, 'imposto_id', 'id');
    }

    public function motivo()
    {
        return $this->belongsTo(Motivo::class, 'motivo_id', 'id');
    }
    
    public function controle()
    {
        return $this->hasOne(ControloSistema::class);
    }
    
    public function tipo_entidade()
    {
        return $this->hasOne(TipoEntidade::class, 'id', 'tipo_id');
    }
    
    public function configuracao_impressora()
    {
        return $this->hasOne(ConfiguracaoEmpressora::class, 'id', 'entidade_id');
    }
    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function marcas()
    {
        return $this->hasMany(Marca::class);
    }

    public function variacoes()
    {
        return $this->hasMany(Variacao::class);
    }
    
    public function caixas()
    {
        return $this->hasMany(Caixa::class);
    }
    
    public function lojas()
    {
        return $this->hasMany(Loja::class);
    }

    public function categorias()
    {
        return $this->hasMany(Categoria::class);
    }
    
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function estoques()
    {
        return $this->hasMany(Estoque::class);
    }
    
    public function dias_licencas($id)
    {
        date_default_timezone_set('Africa/Luanda');
        /*sistema de datas*/
        $dia = @date("d");
        $mes = @date("m");
        $ano = @date("Y");
        $dataFinal = $ano."-".$mes."-".$dia;
        
        $controlo = Entidade::with(['tipo_entidade', 'controle'])->findOrFail($id);
       
        $date1 = date_create($controlo->controle->final);
        $date2 = date_create($dataFinal);
        // $date2 = date_create($controlo->inicio);
        $diff = date_diff($date1,$date2);
        $diasRestantes = $diff->format("%a");
        
        return $diasRestantes;
    }
    
    public function tem_permissao(string $string)
    {
        // Converte os módulos para uma coleção se não forem uma
        $modulos = collect($this->tipo_entidade->modulos);
        
        // Verifica se o nome do módulo existe na coleção de módulos
        return $modulos->contains(function($modulo) use ($string) {
            return $modulo->modulo === $string; // Supondo que o módulo tenha uma propriedade 'nome'
        });
        
        // Verifica se a string está presente no array
        // return in_array($string, $modulos);
    }
}
