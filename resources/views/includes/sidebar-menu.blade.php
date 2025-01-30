<!-- Sidebar Menu -->
@php
$listaCaixas = App\Models\Caixa::where([
['active', true],
['user_id', '=', Auth::user()->id],
])->get();

@endphp

<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ Route::currentRouteNamed('dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-door-open"></i>
                <p>
                    Bem-vindo
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('dashboard-principal') }}" class="nav-link {{ Route::currentRouteNamed('dashboard-principal') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>
                    Dashboard
                </p>
            </a>
        </li>

        @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Produto"))
        <li class="nav-item {{ Request::is('prds*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('prds*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                    Produtos
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                @if (Auth::user()->can('listar produtos'))
                <li class="nav-item">
                    <a href="{{ route('produtos.index') }}" class="nav-link {{ Route::currentRouteNamed('produtos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Produtos</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('criar stock'))
                <li class="nav-item">
                    <a href="{{ route('estoques.index') }}" class="nav-link {{ Route::currentRouteNamed('estoques.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Analise Stock</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar stock'))
                <li class="nav-item">
                    <a href="{{ route('movimento-estoques.index') }}" class="nav-link {{ Route::currentRouteNamed('movimento-estoques.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Movimentos de Stock</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar categoria'))
                <li class="nav-item">
                    <a href="{{ route('categorias.index') }}" class="nav-link {{ Route::currentRouteNamed('categorias.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Categoria</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar marca'))
                <li class="nav-item">
                    <a href="{{ route('marcas.index') }}" class="nav-link {{ Route::currentRouteNamed('marcas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Marca</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar variacoes'))
                <li class="nav-item">
                    <a href="{{ route('variacoes.index') }}" class="nav-link {{ Route::currentRouteNamed('variacoes.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Variações</p>
                    </a>
                </li>
                @endif

            </ul>
        </li>
        @endif

        @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Vendas"))
        <li class="nav-item {{ Request::is('rgst*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('rgst*') ? 'active' : '' }}">
                <i class="nav-icon far fa-folder"></i>
                <p>
                    Registro
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('contabilidade-inventario') }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-inventario') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Inventário Incial</p>
                    </a>
                </li>
                @if (Auth::user()->can('movimento no caixa'))
                <li class="nav-item">
                    <a href="{{ route('contabilidade-diarios') }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-diarios') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Registro de movimentos</p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->can('movimento no caixa geral'))
                <li class="nav-item">
                    <a href="{{ route('contabilidade-facturacao') }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-facturacao') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Facturação</p>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif


        @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Fluxo de Caixa"))
        <li class="nav-item {{ Request::is('flcai*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('flcai*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-boxes"></i>
                <p>
                    Fluxo de Caixa
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                @if (Auth::user()->can('listar caixa'))
                <li class="nav-item">
                    <a href="{{ route('caixas.index') }}" class="nav-link {{ Route::currentRouteNamed('caixas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Todos</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('abertura do caixa'))
                <li class="nav-item">
                    <a href="{{ route('caixa.abertura_caixa') }}" class="nav-link {{ Route::currentRouteNamed('caixa.abertura_caixa') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Abertura de Caixa</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('fecho do caixa'))
                <li class="nav-item">
                    <a href="{{ route('caixa.fechamento_caixa') }}" class="nav-link {{ Route::currentRouteNamed('caixa.fechamento_caixa') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Fecho de Caixa</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('entrada valor no caixa'))
                <li class="nav-item">
                    <a href="{{ route('caixa.entrada_dinheiro_caixa') }}" class="nav-link {{ Route::currentRouteNamed('caixa.entrada_dinheiro_caixa') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Entrada de Caixa</p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->can('saida valor no caixa'))
                <li class="nav-item">
                    <a href="{{ route('caixa.saida_dinheiro_caixa') }}" class="nav-link {{ Route::currentRouteNamed('caixa.saida_dinheiro_caixa') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Saída de Caixa</p>
                    </a>
                </li>
                @endif

            </ul>
        </li>
        @endif

        @if ($tipo_entidade_logado->empresa->tem_permissao("Controle Financeiro"))
        <li class="nav-item {{ Request::is('financ*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('financ*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-boxes"></i>
                <p>
                    Financeiros
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ route('dashboard-financeiro') }}" class="nav-link {{ Route::currentRouteNamed('dashboard-financeiro') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Controlo</p>
                    </a>
                </li>

                @if (Auth::user()->can('listar receita'))
                    <li class="nav-item">
                        <a href="{{ route('receitas.index') }}" class="nav-link {{ Route::currentRouteNamed('receitas.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Tipos de Receitas</p>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->can('listar dispesa'))
                    <li class="nav-item">
                        <a href="{{ route('dispesas.index') }}" class="nav-link {{ Route::currentRouteNamed('dispesas.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Tipos de Dispesas</p>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->can('operacao financeira'))
                    <li class="nav-item">
                        <a href="{{ route('operacaoes-financeiras.index') }}" class="nav-link {{ Route::currentRouteNamed('operacaoes-financeiras.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Transações</p>
                        </a>
                    </li>
                @endif
    
                <li class="nav-item">
                    <a href="{{ route('operacaoes-financeiras.lixeira') }}" class="nav-link {{ Route::currentRouteNamed('operacaoes-financeiras.lixeira') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Lixeira</p>
                    </a>
                </li>

        </ul>
    </li>
    @endif

    @if (Auth::user()->can('gerar saft'))
    <li class="nav-item {{ Request::is('agts*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('agts*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-pdf"></i>
            <p>
                AGT
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('agt.index') }}" class="nav-link {{ Route::currentRouteNamed('agt.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Gerar Ficheiro SAF-T</p>
                </a>
            </li>

        </ul>
    </li>
    @endif


    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Factura"))
    <li class="nav-item {{ Request::is('facturs*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('facturs*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-folder-open"></i>
            <p>
                Facturas
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @if (Auth::user()->can('criar facturas'))
            <li class="nav-item">
                <a href="{{ route('facturas.create') }}" class="nav-link {{ Route::currentRouteNamed('facturas.create') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Criar Documento</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar facturas'))
            <li class="nav-item">
                <a href="{{ route('facturas.index') }}" class="nav-link {{ Route::currentRouteNamed('facturas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Todos</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('facturas-facturacao') }}" class="nav-link {{ Route::currentRouteNamed('facturas-facturacao') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Facturação</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('facturas-informativo') }}" class="nav-link {{ Route::currentRouteNamed('facturas-informativo') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Informativo</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('facturas-sem-pagamento') }}" class="nav-link {{ Route::currentRouteNamed('facturas-sem-pagamento') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Facturas sem Pagamento</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('recibos') }}" class="nav-link {{ Route::currentRouteNamed('recibos') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Recibos</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('notas-creditos') }}" class="nav-link {{ Route::currentRouteNamed('notas-creditos') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Notas Créditos</p>
                </a>
            </li>
            @endif

        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Paciente") || $tipo_entidade_logado->empresa->tem_permissao("Gestão Hospitalar"))
    <li class="nav-item {{ Request::is('clients*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('clients*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
                Pacientes
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('clientes.index') }}" class="nav-link {{ Route::currentRouteNamed('clientes.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listagem</p>
                </a>
            </li>
        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Medico") || $tipo_entidade_logado->empresa->tem_permissao("Gestão Hospitalar"))
    <li class="nav-item {{ Request::is('medics*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('medics*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
                Médicos
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('medicos.index') }}" class="nav-link {{ Route::currentRouteNamed('medicos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listagem</p>
                </a>
            </li>
        </ul>
    </li>
    @endif
    

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Enfermeiro") || $tipo_entidade_logado->empresa->tem_permissao("Gestão Hospitalar"))
    <li class="nav-item {{ Request::is('enferms*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('enferms*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
                Enfermeiros
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('enfermeiros.index') }}" class="nav-link {{ Route::currentRouteNamed('enfermeiros.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listagem</p>
                </a>
            </li>
        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Triangem") || $tipo_entidade_logado->empresa->tem_permissao("Gestão Hospitalar"))
    <li class="nav-item {{ Request::is('triangs*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('triangs*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
                Triagens
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            <li class="nav-item">
                <a href="{{ route('triagens.index') }}" class="nav-link {{ Route::currentRouteNamed('triagens.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listagem</p>
                </a>
            </li>

        </ul>
    </li>
    @endif


    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Contabilidade"))

    <li class="nav-item {{ Request::is('contabils*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('contabils*') ? 'active' : '' }}">
            <i class="nav-icon far fa-plus-square"></i>
            <p>
                Contabilidade
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item {{ Request::is('contabils*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                        Plano de contas
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @if (Auth::user()->can('listar conta'))
                    <li class="nav-item">
                        <a href="{{ route('plano-geral-contas.index') }}" class="nav-link {{ Route::currentRouteNamed('plano-geral-contas.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>P.G.C</p>
                        </a>
                    </li>
                    @endif

                    @if (Auth::user()->can('listar classe'))
                    <li class="nav-item">
                        <a href="{{ route('classes.index') }}" class="nav-link {{ Route::currentRouteNamed('classes.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Classes</p>
                        </a>
                    </li>
                    @endif

                    @if (Auth::user()->can('listar conta'))
                    <li class="nav-item">
                        <a href="{{ route('contas.index') }}" class="nav-link {{ Route::currentRouteNamed('contas.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Contas</p>
                        </a>
                    </li>
                    @endif

                    @if (Auth::user()->can('listar subconta'))
                    <li class="nav-item">
                        <a href="{{ route('subcontas.index') }}" class="nav-link {{ Route::currentRouteNamed('subcontas.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Subcontas</p>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            
            <li class="nav-item {{ Request::is('contabils*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                        Operações
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('contabilidade-balanco-inicial') }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-balanco-inicial') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Balanço Inicial</p>
                        </a>
                    </li>
                                        
                    <li class="nav-item">
                        <a href="{{ route('contabilidade-balancete') }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-balancete') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Balancente</p>
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                        Inventários
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('inventarios.index') }}" class="nav-link {{ Route::currentRouteNamed('inventarios.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Todos</p>
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="nav-item {{ Request::is('contabils*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                        Equipamentos/Activos
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('equipamentos-activos.index') }}" class="nav-link {{ Route::currentRouteNamed('equipamentos-activos.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Todos</p>
                        </a>
                    </li>
                </ul>
            </li>

            @if (Auth::user()->can('listar exercicio'))
            <li class="nav-item">
                <a href="{{ route('exercicios.index') }}" class="nav-link {{ Route::currentRouteNamed('exercicios.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Exercício</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar periodo'))
            <li class="nav-item">
                <a href="{{ route('periodos.index') }}" class="nav-link {{ Route::currentRouteNamed('periodos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Periodos</p>
                </a>
            </li>
            @endif
            
            @if (Auth::user()->can('listar banco'))
            <li class="nav-item">
                <a href="{{ route('tipos-creditos.index') }}" class="nav-link {{ Route::currentRouteNamed('tipos-creditos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tipos de Créditos</p>
                </a>
            </li>
            @endif
            
            @if (Auth::user()->can('listar banco'))
            <li class="nav-item">
                <a href="{{ route('contrapartidas.index') }}" class="nav-link {{ Route::currentRouteNamed('contrapartidas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Contrapartidas</p>
                </a>
            </li>
            @endif
            
            @if (Auth::user()->can('listar banco'))
            <li class="nav-item">
                <a href="{{ route('taxas-reintegracao-amortizacoes.index') }}" class="nav-link {{ Route::currentRouteNamed('taxas-reintegracao-amortizacoes.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Taxas Rein.Amort</p>
                </a>
            </li>
            @endif
            
        </ul>
    </li>

    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Recurso Humano"))
    <li class="nav-item {{ Request::is('reshums*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('reshums*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
                Recursos Humanos
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            <li class="nav-item">
                <a href="{{ route('dashboard-recurso-humanos') }}" class="nav-link {{ Route::currentRouteNamed('dashboard-recurso-humanos') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Controlo</p>
                </a>
            </li>

            @if (Auth::user()->can('listar categoria'))
            <li class="nav-item">
                <a href="{{ route('categorias-cargos.index') }}" class="nav-link {{ Route::currentRouteNamed('categorias-cargos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Categorias</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar departamento'))
            <li class="nav-item">
                <a href="{{ route('departamentos.index') }}" class="nav-link {{ Route::currentRouteNamed('departamentos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Departamentos</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar cargo'))
            <li class="nav-item">
                <a href="{{ route('cargos.index') }}" class="nav-link {{ Route::currentRouteNamed('cargos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Cargos</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar rendimento'))
            <li class="nav-item">
                <a href="{{ route('tipos-rendimentos.index') }}" class="nav-link {{ Route::currentRouteNamed('tipos-rendimentos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tipos de Rendimento</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar periodo'))
            <li class="nav-item">
                <a href="{{ route('periodos-rendimentos.index') }}" class="nav-link {{ Route::currentRouteNamed('periodos-rendimentos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Período Rendimento</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar tipo contrato'))
            <li class="nav-item">
                <a href="{{ route('tipos-contratos.index') }}" class="nav-link {{ Route::currentRouteNamed('tipos-contratos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tipos de Contrato</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar processamento'))
            <li class="nav-item">
                <a href="{{ route('tipos-processamentos.index') }}" class="nav-link {{ Route::currentRouteNamed('tipos-processamentos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tipos de Processamento</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar subsidio'))
            <li class="nav-item">
                <a href="{{ route('subsidios.index') }}" class="nav-link {{ Route::currentRouteNamed('subsidios.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Subsídios</p>
                </a>
            </li>
            @endif


            @if (Auth::user()->can('listar desconto'))
            <li class="nav-item">
                <a href="{{ route('descontos.index') }}" class="nav-link {{ Route::currentRouteNamed('descontos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Descontos</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar funcionario'))
            <li class="nav-item">
                <a href="{{ route('funcionarios.index') }}" class="nav-link {{ Route::currentRouteNamed('funcionarios.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Funcionários</p>
                </a>
            </li>
            @endif


        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Cliente"))
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>
                @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Hotelaria"))
                Hospodes
                @else
                Clientes
                @endif
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @if (Auth::user()->can('listar cliente'))
            <li class="nav-item">
                <a href="{{ route('clientes.index') }}" class="nav-link {{ Route::currentRouteNamed('clientes.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Todos</p>
                </a>
            </li>
            @endif

            <li class="nav-item">
                <a href="{{ route('conta-clientes.index') }}" class="nav-link {{ Route::currentRouteNamed('conta-clientes.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Conta Corrente</p>
                </a>
            </li>
        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Matriculas"))
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>
                Matriculas
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            <li class="nav-item">
                <a href="{{ route('alunos-matriculas') }}" class="nav-link {{ Route::currentRouteNamed('alunos-matriculas') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listagem</p>
                </a>
            </li>

        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Aluno"))
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>
                Alunos
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            <li class="nav-item">
                <a href="{{ route('alunos.index') }}" class="nav-link {{ Route::currentRouteNamed('alunos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Alunos</p>
                </a>
            </li>

        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Formadores"))
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>
                Formadores
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            <li class="nav-item">
                <a href="{{ route('formadores.index') }}" class="nav-link {{ Route::currentRouteNamed('formadores.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listagem</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('videos.home') }}" class="nav-link {{ Route::currentRouteNamed('videos.home') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Vídeos/Conteúdo</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('provas.index') }}" class="nav-link {{ Route::currentRouteNamed('provas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Provas</p>
                </a>
            </li>

        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Stock"))
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-truck"></i>
            <p>
                Compras
                <i class="right fas fa-angle-left"></i>

            </p>
        </a>
        <ul class="nav nav-treeview">

            @if (Auth::user()->can('listar fornecedores'))
            <li class="nav-item">
                <a href="{{ route('fornecedores.index') }}" class="nav-link {{ Route::currentRouteNamed('fornecedores.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Fornecedores</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar encomendas'))
            <li class="nav-item">
                <a href="{{ route('fornecedores-encomendas.index') }}" class="nav-link {{ Route::currentRouteNamed('fornecedores-encomendas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Encomendas</p>
                </a>
            </li>
            @endif

            <li class="nav-item">
                <a href="{{ route('requisacoes.index') }}" class="nav-link {{ Route::currentRouteNamed('requisacoes.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Requisações</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('fornecedores-facturas-encomendas.index') }}" class="nav-link {{ Route::currentRouteNamed('fornecedores-facturas-encomendas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Factura</p>
                </a>
            </li>
        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Consultas"))
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-file-excel"></i>
            <p>
                Agendamentos
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @if (Auth::user()->can('listar agendamento'))
            <li class="nav-item">
                <a href="{{ route('agendamentos.index') }}" class="nav-link {{ Route::currentRouteNamed('agendamentos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Controle</p>
                </a>
            </li>
            @endif

        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Relatório Hotelaria"))
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-file-excel"></i>
            <p>
                Relatórios
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('reservas.index') }}" class="nav-link {{ Route::currentRouteNamed('reservas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Reservas</p>
                </a>
            </li>
        </ul>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('clientes.index') }}" class="nav-link {{ Route::currentRouteNamed('clientes.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Hospedes</p>
                </a>
            </li>
        </ul>
    </li>
    @else
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-file-excel"></i>
            <p>
                Relatórios
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Vendas"))
                <li class="nav-item">
                    <a href="{{ route('vendas_por_produtos') }}" class="nav-link {{ Route::currentRouteNamed('vendas_por_produtos') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Produtos</p>
                    </a>
                </li>
            @endif

            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Vendas"))
                <li class="nav-item">
                    <a href="{{ route('relatorio-cliente-pdf') }}" class="nav-link {{ Route::currentRouteNamed('relatorio-cliente-pdf') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Clientes</p>
                    </a>
                </li>
    
                @if (Auth::user()->can('listar vendas'))
                <li class="nav-item">
                    <a href="{{ route('vendas_por_produtos') }}" class="nav-link {{ Route::currentRouteNamed('vendas_por_produtos') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Produtos</p>
                    </a>
                </li>
                @endif
            @endif

            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Vendas"))
                @if (Auth::user()->can('movimento no caixa geral'))
                <li class="nav-item">
                    <a href="{{ route('caixa.movimentos_caixa') }}" class="nav-link {{ Route::currentRouteNamed('caixa.movimentos_caixa') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Movimentos do Caixa</p>
                    </a>
                </li>
                @endif
            @endif

            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Stock"))
            
                @if (Auth::user()->can('listar stock'))
                <li class="nav-item">
                    <a href="{{ route('vendas_por_artigo') }}" class="nav-link {{ Route::currentRouteNamed('vendas_por_artigo') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Stock Por Artigo</p>
                    </a>
                </li>
    
                <li class="nav-item">
                    <a href="{{ route('vendas_por_artigo_anterior') }}" class="nav-link {{ Route::currentRouteNamed('vendas_por_artigo_anterior') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Stock Por Artigo Anterior</p>
                    </a>
                </li>
                @endif
    
                @if (Auth::user()->can('listar loja/armazem'))
                <li class="nav-item">
                    <a href="{{ route('lojas.index') }}" class="nav-link {{ Route::currentRouteNamed('lojas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Lojas/Armazém</p>
                    </a>
                </li>
                @endif
    
                @if (Auth::user()->can('gestao loja/armazem'))
                <li class="nav-item">
                    <a href="{{ route('gestao-lojas-armazem') }}" class="nav-link {{ Route::currentRouteNamed('gestao-lojas-armazem') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Gestão de Lojas/Armazém</p>
                    </a>
                </li>
    
                <li class="nav-item">
                    <a href="{{ route('transferencia-lojas-armazem') }}" class="nav-link {{ Route::currentRouteNamed('transferencia-lojas-armazem') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Transferência de Loja/Armazém</p>
                    </a>
                </li>
                @endif

            @endif

            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Vendas"))
                @if (Auth::user()->can('listar vendas'))
                <li class="nav-item">
                    <a href="{{ route('vendas_produtos') }}" class="nav-link {{ Route::currentRouteNamed('vendas_produtos') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Vendas</p>
                    </a>
                </li>
                @endif
            @endif
            
            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Vendas"))

                @if (Auth::user()->can('movimento no caixa'))
                <li class="nav-item">
                    <a href="{{ route('contabilidade-diarios') }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-diarios') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Diário</p>
                    </a>
                </li>
                @endif
    
                @if (Auth::user()->can('listar produtos'))
                <li class="nav-item">
                    <a href="{{ route('produtos-compras.index') }}" class="nav-link {{ Route::currentRouteNamed('produtos-compras.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Produtos</p>
                    </a>
                </li>
                @endif
    
                @if (Auth::user()->can('listar produtos'))
                <li class="nav-item">
                    <a href="{{ route('registros-compras-produtos.index') }}" class="nav-link {{ Route::currentRouteNamed('registros-compras-produtos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Registros compras</p>
                    </a>
                </li>
                @endif
            @endif

        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Permissão"))
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
                Gestão de Permissão
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            <li class="nav-item">
                <a href="{{ route('roles.index') }}" class="nav-link {{ Route::currentRouteNamed('roles.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Perfis</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('permissoes.index') }}" class="nav-link {{ Route::currentRouteNamed('permissoes.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Permissões</p>
                </a>
            </li>

        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Tabela Apoio Hotelaria"))
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
                Tabela de Apoio
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
        
            @if (Auth::user()->can('listar tarefario'))
            <li class="nav-item">
                <a href="{{ route('tarefarios.index') }}" class="nav-link {{ Route::currentRouteNamed('tarefarios.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tarifários</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar tipo quarto'))
            <li class="nav-item">
                <a href="{{ route('tipo-quartos.index') }}" class="nav-link {{ Route::currentRouteNamed('tipo-quartos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tipo de Quartos</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar andar'))
            <li class="nav-item">
                <a href="{{ route('andares.index') }}" class="nav-link {{ Route::currentRouteNamed('andares.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Andares</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar utilizadores'))
            <li class="nav-item">
                <a href="{{ route('utilizadores.index') }}" class="nav-link {{ Route::currentRouteNamed('utilizadores.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Utilizadores</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar seguradora'))
            <li class="nav-item">
                <a href="{{ route('seguradoras.index') }}" class="nav-link {{ Route::currentRouteNamed('seguradoras.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Seguradoras</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar banco'))
            <li class="nav-item">
                <a href="{{ route('contas-bancarias.index') }}" class="nav-link {{ Route::currentRouteNamed('contas-bancarias.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Contas Bancárias</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar caixa'))
            <li class="nav-item">
                <a href="{{ route('caixas.index') }}" class="nav-link {{ Route::currentRouteNamed('caixas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Caixas</p>
                </a>
            </li>
            @endif

        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Tabela Apoio"))
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
                Tabela de Apoio
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @if (Auth::user()->can('listar utilizadores'))
            <li class="nav-item">
                <a href="{{ route('utilizadores.index') }}" class="nav-link {{ Route::currentRouteNamed('utilizadores.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Utilizadores</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar seguradora'))
            <li class="nav-item">
                <a href="{{ route('seguradoras.index') }}" class="nav-link {{ Route::currentRouteNamed('seguradoras.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Seguradoras</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar banco'))
            <li class="nav-item">
                <a href="{{ route('contas-bancarias.index') }}" class="nav-link {{ Route::currentRouteNamed('contas-bancarias.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Contas Bancárias</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar caixa'))
            <li class="nav-item">
                <a href="{{ route('caixas.index') }}" class="nav-link {{ Route::currentRouteNamed('caixas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Caixas</p>
                </a>
            </li>
            @endif

        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Tabela Apoio Centro Formação"))
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
                Tabela de Apoio
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @if (Auth::user()->can('listar utilizadores'))
            <li class="nav-item">
                <a href="{{ route('utilizadores.index') }}" class="nav-link {{ Route::currentRouteNamed('utilizadores.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Utilizadores</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar ano lectivo'))
            <li class="nav-item">
                <a href="{{ route('anos-lectivos.index') }}" class="nav-link {{ Route::currentRouteNamed('anos-lectivos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Anos Lectivos</p>
                </a>
            </li>
            @endif

            {{-- @if (Auth::user()->can('listar curso')) --}}
            <li class="nav-item">
                <a href="{{ route('anuncios.index') }}" class="nav-link {{ Route::currentRouteNamed('anuncios.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Anuncios</p>
                </a>
            </li>
            {{-- @endif --}}

            @if (Auth::user()->can('listar curso'))
            <li class="nav-item">
                <a href="{{ route('cursos.index') }}" class="nav-link {{ Route::currentRouteNamed('cursos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Cursos</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar turno'))
            <li class="nav-item">
                <a href="{{ route('turnos.index') }}" class="nav-link {{ Route::currentRouteNamed('turnos.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Turnos</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar sala'))
            <li class="nav-item">
                <a href="{{ route('salas.index') }}" class="nav-link {{ Route::currentRouteNamed('salas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Salas</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar turma'))
            <li class="nav-item">
                <a href="{{ route('turmas.index') }}" class="nav-link {{ Route::currentRouteNamed('turmas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Turmas</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar seguradora'))
            <li class="nav-item">
                <a href="{{ route('seguradoras.index') }}" class="nav-link {{ Route::currentRouteNamed('seguradoras.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Seguradoras</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar banco'))
            <li class="nav-item">
                <a href="{{ route('contas-bancarias.index') }}" class="nav-link {{ Route::currentRouteNamed('contas-bancarias.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Contas Bancárias</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar caixa'))
            <li class="nav-item">
                <a href="{{ route('caixas.index') }}" class="nav-link {{ Route::currentRouteNamed('caixas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Caixas</p>
                </a>
            </li>
            @endif

        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Tabela Apoio Vendas"))
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
                Tabela de Apoio
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @if (Auth::user()->can('gestao loja/armazem'))
            <li class="nav-item">
                <a href="{{ route('gestao-lojas-armazem') }}" class="nav-link {{ Route::currentRouteNamed('gestao-lojas-armazem') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Gestão de Lojas/Armazém</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('transferencia-lojas-armazem') }}" class="nav-link {{ Route::currentRouteNamed('transferencia-lojas-armazem.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Transferência de Loja/Armazém</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar loja/armazem'))
            <li class="nav-item">
                <a href="{{ route('lojas.index') }}" class="nav-link {{ Route::currentRouteNamed('lojas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Lojas/Armazém</p>
                </a>
            </li>
            @endif


            @if (Auth::user()->can('listar utilizadores'))
            <li class="nav-item">
                <a href="{{ route('utilizadores.index') }}" class="nav-link {{ Route::currentRouteNamed('utilizadores.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Utilizadores</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar pin'))
            <li class="nav-item">
                <a href="{{ route('pins.index') }}" class="nav-link {{ Route::currentRouteNamed('pins.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Pins</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar lote'))
            <li class="nav-item">
                <a href="{{ route('lotes.index') }}" class="nav-link {{ Route::currentRouteNamed('lotes.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Lotes</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar banco'))
            <li class="nav-item">
                <a href="{{ route('contas-bancarias.index') }}" class="nav-link {{ Route::currentRouteNamed('contas-bancarias.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Contas Bancárias</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar caixa'))
            <li class="nav-item">
                <a href="{{ route('caixas.index') }}" class="nav-link {{ Route::currentRouteNamed('caixas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Caixas</p>
                </a>
            </li>
            @endif

            <li class="nav-item">
                <a href="{{ route('salas.index') }}" class="nav-link {{ Route::currentRouteNamed('salas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Salas e Mesas</p>
                </a>
            </li>

        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Tabela Apoio Hospitalar"))
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
                Tabela de Apoio
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @if (Auth::user()->can('listar utilizadores'))
            <li class="nav-item">
                <a href="{{ route('utilizadores.index') }}" class="nav-link {{ Route::currentRouteNamed('utilizadores.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Utilizadores</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar lote'))
            <li class="nav-item">
                <a href="{{ route('lotes.index') }}" class="nav-link {{ Route::currentRouteNamed('lotes.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Lotes</p>
                </a>
            </li>
            @endif

            <li class="nav-item">
                <a href="{{ route('seguradoras.index') }}" class="nav-link {{ Route::currentRouteNamed('seguradoras.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Seguradoras</p>
                </a>
            </li>

            @if (Auth::user()->can('listar banco'))
            <li class="nav-item">
                <a href="{{ route('contas-bancarias.index') }}" class="nav-link {{ Route::currentRouteNamed('contas-bancarias.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Contas Bancárias</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar caixa'))
            <li class="nav-item">
                <a href="{{ route('caixas.index') }}" class="nav-link {{ Route::currentRouteNamed('caixas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Caixas</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('listar loja/armazem'))
            <li class="nav-item">
                <a href="{{ route('lojas.index') }}" class="nav-link {{ Route::currentRouteNamed('lojas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Lojas/Armazém</p>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('gestao loja/armazem'))
            <li class="nav-item">
                <a href="{{ route('gestao-lojas-armazem') }}" class="nav-link {{ Route::currentRouteNamed('gestao-lojas-armazem.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Gestão de Lojas/Armazém</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('transferencia-lojas-armazem') }}" class="nav-link {{ Route::currentRouteNamed('transferencia-lojas-armazem.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Transferência de Loja/Armazém</p>
                </a>
            </li>
            @endif

            <li class="nav-item">
                <a href="{{ route('salas.index') }}" class="nav-link {{ Route::currentRouteNamed('salas.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Salas e Mesas</p>
                </a>
            </li>

        </ul>
    </li>
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Vendas"))
    @if (Auth::user()->can('criar vendas'))
    <li class="nav-item">
        <a href="{{ route('pronto-venda') }}" class="nav-link {{ Route::currentRouteNamed('pronto-venda') ? 'active' : '' }}">
            <i class="nav-icon fas fa-desktop"></i>
            <p>
                Inicio Venda
            </p>
        </a>
    </li>
    @endif
    @endif

    @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Vendas Pedido"))
    @if (Auth::user()->can('criar vendas'))
    <li class="nav-item">
        <a href="{{ route('pronto-venda-mesas') }}" class="nav-link {{ Route::currentRouteNamed('pronto-venda-mesas') ? 'active' : '' }}">
            <i class="nav-icon fas fa-desktop"></i>
            <p>
                Inicio Venda Pedido
            </p>
        </a>
    </li>
    @endif
    @endif

    <li class="nav-item">
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('formLoggout').submit();" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>
                Terminar sessão
                {{-- <span class="right badge badge-danger">New</span> --}}
            </p>
        </a>
        <form action="{{ route('logout') }}" id="formLoggout" method="post" class="d-none">@csrf </form>
    </li>

    </ul>
</nav>
<!-- /.sidebar-menu -->
