<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



// Criar contas e subcontas de forma dinâmica
$plano_geral_de_contas = [
    
    '11' => [
        'nome' => 'Imobilizações corpóreas',
        'subcontas' => [
            '11.1' => 'Terrenos e recursos naturais',
            '11.1.1' => 'Terrenos em bruto',
            '11.1.2' => 'Terrenos com arranjos',
            '11.1.3' => 'Subsolos',
            '11.1.4' => 'Terrenos com edifícios',
            '11.1.4.1' => 'Relativos a edifícios industriais',
            '11.1.4.2' => 'Relativos a edifícios administrativos e comerciais',
            '11.1.4.3' => 'Relativos a outros edifícios',
            '11.2' => 'Edifícios e outras construções',
            '11.2.1' => 'Edifícios',
            '11.2.1.1' => 'Integrados em conjuntos industriais',
            '11.2.1.2' => 'Integrados em conjuntos administrativos e comerciais',
            '11.2.1.3' => 'Outros conjuntos industriais',
            '11.2.1.4' => 'Implantados em propriedade alheia',
            '11.2.2' => 'Outras construções',
            '11.2.3' => 'Instalações',
            '11.3' => 'Equipamento básico',
            '11.3.1' => 'Material industria',
            '11.3.2' => 'Ferramentas industriais',
            '11.3.3' => 'Melhoramentos em equipamentos básicos',
            '11.4' => 'Equipamento de carga e transporte',
            '11.5' => 'Equipamento administrativo',
            '11.6' => 'Equipamento administrativo',
            '11.9' => 'Equipamento administrativo',
        ].
    ],
    '12' => [
        'nome' => 'Imobilizações incorpóreas',
        'subcontas' => [
            '12.1' => 'Trespasses',
            '12.3' => 'Propriedade industrial e outros direitos e contratos',
            '12.4' => 'Despesas de constituição',
            '12.9' => 'Outras imobilizações incorpóreas',
        ],
    ],
    '13' => [
        'nome' => 'Investimentos financeiros',
        'subcontas' => [
            '13.1' => 'Empresas subsidiárias',
            '13.1.1' => 'Partes de capital',
            '13.1.2' => 'Obrigações e títulos de participação',
            '13.1.3' => 'Empréstimos',
            '13.2' => 'Empresas associadas',
            '13.2.1' => 'Partes de capital',
            '13.2.2' => 'Obrigações e títulos de participação',
            '13.2.3' => 'Empréstimos',
            '13.3' => 'Outras empresas',
            '13.3.1' => 'Partes de capital',
            '13.3.2' => 'Obrigações e títulos de participação',
            '13.3.3' => 'Empréstimos',
            '13.4' => 'Investimentos em imóveis',
            '13.5' => 'Fundos',
            '13.9' => 'Outros investimentos Financeiros',
            '13.9.1' => 'Diamantes',
            '13.9.2' => 'Ouro',
            '13.9.3' => 'Depósitos bancários',
        ],
    ],
    '14' => [
        'nome' => 'Imobilizações em curso',
        'subcontas' => [
            '14.1' => 'Obra em curso',
            '14.2' => 'Obra em curso',
            '14.7' => 'Adiantamentos por conta de imobilizado corpóreo',
            '14.8' => 'Adiantamentos por conta de imobilizado incorpóreo',
            '14.9' => 'Adiantamentos por conta de investimentos financeiros',
        ],
    ],
    '18' => [
        'nome' => 'Amortizações acumuladas',
        'subcontas' => [
            '18.1' => 'Imobilizações corpóreas',
            '18.1.1' => 'Terrenos e recursos naturais',
            '18.1.2' => 'Edifícios e outras construções',
            '18.1.3' => 'Equipamento básico',
            '18.1.4' => 'Equipamento de carga e transporte',
            '18.1.5' => 'Equipamento administrativo',
            '18.1.6' => 'Taras e vasilhame',
            '18.1.9' => 'Outras imobilizações corpóreas',
            '18.2' => 'Imobilizações incorpóreas',
            '18.2.1' => 'Trespasses',
            '18.2.2' => 'Despesas de investigação e desenvolvimento',
            '18.2.3' => 'Propriedade industrial e outros direitos e contratos',
            '18.2.4' => 'Despesas de constituição',
            '18.2.9' => 'Outras imobilizações incorpóreas',
            '18.3' => 'Investimentos financeiros em imóveis',
            '18.3.1' => 'Terrenos e recursos naturais',
            '18.3.2' => 'Edifícios e outras construções',
        ],
    ],
    '19' => [
        'nome' => '',
        'subcontas' => [
            '19.1' => 'Empresas subsidiárias"',
            '19.1.1' => 'Partes de capital',
            '19.1.2' => 'Obrigações e títulos de participação',
            '19.1.3' => 'Empréstimos',
            '19.2' => 'Empresas associadas',
            '19.2.1' => 'Partes de capital',
            '19.2.2' => 'Obrigações e títulos de participação',
            '19.2.3' => 'Empréstimos',
            '19.3' => 'Outras empresas',
            '19.3.1' => 'Partes de capital',
            '19.3.2' => 'Obrigações e títulos de participação',
            '19.3.3' => 'Empréstimos',
            '19.4' => 'Fundos',
            '19.4.1' => 'Partes de capital',
            '19.9' => 'Outros investimentos financeiros',
            '19.9.1' => 'Diamantes',
            '19.9.2' => 'Ouro',
            '19.9.3' => 'Depósitos bancários',
        ],
    ],
    
    '21' => [
        'nome' => 'Compras',
        'subcontas' => [
            '21.1' => 'Matérias-primas, subsidiárias e de consumo',
            '21.2' => 'Mercadorias',
            '21.7' => 'Devoluções de compras',
            '21.8' => 'Descontos e abatimentos em compras',
        ],
    ],
    
    '22' => [
        'nome' => 'Matérias-primas, subsidiárias e de consumo',
        'subcontas' => [
            '22.1' => 'Matérias-primas',
            '22.2' => 'Matérias subsidiárias',
            '22.3' => 'Materiais diversos',
            '22.4' => 'Embalagens de consumo',
            '22.5' => 'Outros materiais',
        ],
    ],
    '23' => [
        'nome' => 'Produtos e trabalhos em curso',
        'subcontas' => [],
    ],
    '24' => [
        'nome' => 'Produtos acabados e intermédios',
        'subcontas' => [
            '24.1' => 'Produtos acabados',
            '24.2' => 'Produtos intermédios',
            '24.9' => 'Em poder de terceiros',
        ],
    ],
    '25' => [
        'nome' => 'Sub-produtos, desperdícios, resíduos e refugos',
        'subcontas' => [
            '25.1' => 'Sub-produtos',
            '25.2' => 'Desperdícios, resíduos e refugos',
        ],
    ],
    '26' => [
        'nome' => 'Mercadorias',
        'subcontas' => [
            '26.9' => 'Em poder de terceiros',
        ],
    ],
    '27' => [
        'nome' => 'Matérias-primas, mercadorias e outros materiais em trânsito',
        'subcontas' => [
            '27.1' => 'Matérias-primas',
            '27.2' => 'Outros materiais',
            '27.3' => 'Mercadorias',
        ],
    ],
    '28' => [
        'nome' => 'Adiantamentos por conta de compras',
        'subcontas' => [
            '28.1' => 'Matérias-primas e outros materiais',
            '28.2' => 'Mercadorias',
        ],
    ],
    '29' => [
        'nome' => 'Provisão para depreciação de existências',
        'subcontas' => [
            '29.2' => 'Matérias-primas subsidiárias e de consumo',
            '29.3' => 'Produtos e trabalhos em curso',
            '29.4' => 'Produtos acabados e intermédios',
            '29.5' => 'Sub-produtos, desperdícios, resíduos e refugos',
            '29.6' => 'Mercadorias',
        ],
    ],
    
    '31' => [
        'nome' => 'Clientes',
        'subcontas' => [
            '31.1' => 'Clientes – correntes',
            '31.1.1' => 'Grupo',
            '31.1.2' => 'Não grupo',
            '31.2' => 'Clientes – títulos a receber',
            '31.2.1' => 'Grupo',
            '31.2.2' => 'Não grupo',
            '31.3' => 'Clientes – títulos descontados',
            '31.3.1' => 'Grupo',
            '31.3.2' => 'Não grupo',
            '31.8' => 'Clientes de cobrança duvidosa',
            '31.8.1' => 'Clientes – correntes',
            '31.8.2' => 'Clientes – títulos',
            '31.9' => 'Clientes - saldos credores',
            '31.9.1' => 'Adiantamento',
            '31.9.2' => 'Embalagens a devolver',
            '31.9.3' => 'Material à consignação',        
        ],
    ],
    
    '32' => [
        'nome' => 'Fornecedores',
        'subcontas' => [
            '32.1' => 'Fornecedores – correntes',
            '32.1.1' => 'Grupo',
            '32.1.2' => 'Não grupo',
            '32.1.2.1' => 'Nacionais',
            '32.1.2.2' => 'Estrangeiros',
            '32.2' => 'Fornecedores – títulos a pagar',
            '32.2.1' => 'Grupo',
            '32.2.1.1' => 'Subsidiárias',
            '32.2.1.2' => 'Associadas',
            '32.2.2' => 'Associadas',
            '32.2.2.1' => 'Nacionais',
            '32.2.2.2' => 'Estrangeiros',
            '32.8' => 'Fornecedores – facturas em recepção e conferência',
            '32.9' => 'Fornecedores – saldos devedores',
        ],
    ],
    
    '33' => [
        'nome' => 'Empréstimos',
        'subcontas' => [
            '33.1' => 'Empréstimos bancários',
            '33.1.1' => 'Moeda nacional',
            '33.1.2' => 'Moeda estrangeira',
            '33.2' => 'Empréstimos por obrigações',
            '33.3' => 'Empréstimos por títulos de participação',
            '33.9' => 'Outros empréstimos obtidos',            
        ],
    ],
    
    '34' => [
        'nome' => 'Estado',
        'subcontas' => [
            '34.1' => 'Imposto sobre os lucros',
            '34.2' => 'Imposto de produção e consumo',
            '34.3' => 'Imposto de rendimento de trabalho',
            '34.4' => 'Imposto de circulação',
            '34.5' => 'IVA',
            '34.5.1' => 'IVA suportado:',
            '34.5.1.1' => 'Existências',
            '34.5.1.2' => 'Meios fixos e investimentos',
            '34.5.1.3' => 'Outros bens e serviço',
            '34.5.2' => 'IVA dedutível',
            '34.5.2.1' => 'Existências',
            '34.5.2.2' => 'Meios fixos e investimentos',
            '34.5.2.3' => 'Outros bens e serviços',
            '34.5.3' => 'IVA liquidado',
            '34.5.3.1' => 'Operações gerais',
            '34.5.3.2' => 'Operações abrangidas pelo regime de IVA de caixa',
            '34.5.3.3' => 'Autoconsumo e operações gratuitas',
            '34.5.3.4' => 'Operações especiais',
            '34.5.4' => 'IVA regularizações',
            '34.5.4.1' => 'Mensais a favor do sujeito passivo',
            '34.5.4.2' => 'Mensais a favor do Estado',
            '34.5.4.3' => 'Anual por cálculo do pró rata definitivo',
            '34.5.4.4' => 'Outras regularizações anuais',
            '34.5.5' => 'IVA apuramento',
            '34.5.5.1' => 'Apuramento do regime de IVA normal',
            '34.5.5.2' => 'Apuramento do regime de IVA de caixa',
            '34.5.6' => 'IVA a pagar',
            '34.5.6.1' => 'IVA a pagar de apuramento',
            '34.5.6.2' => 'IVA a pagar de cativo',
            '34.5.6.3' => 'IVA a pagar de liquidações oficiosas',
            '34.5.7' => 'IVA a recuperar',
            '34.5.7.1' => 'IVA a recuperar de apuramentos',
            '34.5.7.2' => 'IVA a recuperar de cativo',
            '34.5.8' => 'IVA reembolsos pedidos',
            '34.5.8.1' => 'Reembolsos pedidos',
            '34.5.8.2' => 'Reembolsos deferidos',
            '34.5.8.3' => 'Reembolsos indeferidos',
            '34.5.8.4' => 'Reembolsos reclamados, recorridos ou impugnados',
            '34.5.9' => 'IVA Liquidações oficiosas',
            '34.6' => 'Certificado de crédito fiscal a compensar',
            '34.8' => 'Subsídios a preços',
            '34.9' => 'Outros impostos',
        ],
    ],
    
    '35' => [
        'nome' => 'Entidades participantes e participadas',
        'subcontas' => [
            '35.1' => 'Entidades participantes',
            '35.1.1' => 'Estado',
            '35.1.1.1' => 'c/subscrição',
            '35.1.1.2' => 'c/adiantamentos sobre lucros',
            '35.1.1.3' => 'c/lucros',
            '35.1.1.4' => 'Empréstimos',
            '35.1.2' => 'Empresas do grupo – subsidiárias',
            '35.1.2.1' => 'c/subscrição',
            '35.1.2.2' => 'c/adiantamentos sobre lucro',
            '35.1.2.3' => 'c/lucros',
            '35.1.2.4' => 'Empréstimos',
            '35.1.3' => 'Empresas do grupo – associadas',
            '35.1.3.1' => 'c/subscrição',
            '35.1.3.2' => 'c/adiantamentos sobre lucros',
            '35.1.3.3' => 'c/lucros',
            '35.1.3.4' => 'Empréstimos',
            '35.1.4' => 'Outros',
            '35.1.4.1' => 'c/subscrição',
            '35.1.4.2' => 'c/adiantamentos sobre lucros',
            '35.1.4.3' => 'c/lucros',
            '35.1.4.4' => 'Empréstimos',
            '35.2' => 'Entidades participadas',
            '35.2.1' => 'Estado',
            '35.2.1.1' => 'c/subscrição',
            '35.2.1.2' => 'c/adiantamentos sobre lucros',
            '35.2.1.3' => 'c/lucros',
            '35.2.1.4' => 'Empréstimos',
            '35.2.2' => 'Empresas do grupo – subsidiárias',
            '35.2.2.1' => 'c/subscrição',
            '35.2.2.2' => 'c/adiantamentos sobre lucros',
            '35.2.2.3' => 'c/lucros',
            '35.2.2.4' => 'Empréstimos',
            '35.2.3' => 'Empresas do grupo – associadas',
            '35.2.3.1' => 'c/subscrição',
            '35.2.3.2' => 'c/adiantamentos sobre lucros',
            '35.2.3.3' => 'c/lucros',
            '35.2.3.4' => 'Empréstimos',
            '35.2.4' => 'Outros',
            '35.2.4.1' => 'c/subscrição',
            '35.2.4.2' => 'c/adiantamentos sobre lucros',
            '35.2.4.3' => 'c/lucros',
            '35.2.4.4' => 'Empréstimos',
        ],
    ],
    '36' => [
        'nome' => 'Pessoal',
        'subcontas' => [],
    ],
    
    '37' => [
        'nome' => 'Outros valores a receber e a pagar',
        'subcontas' => [
            '37.1' => 'Compras de imobilizado',
            '37.1.1' => 'Corpóreo',
            '37.1.2' => 'Incorpóreo',
            '37.1.3' => 'Financeiro',
            '37.2' => 'Vendas de imobilizado',
            '37.2.1' => 'Corpóreo',
            '37.2.2' => 'Incorpóreo',
            '37.2.3' => 'Financeiro',
            '37.3' => 'Proveitos a facturar',
            '37.3.1' => 'Vendas',
            '37.3.2' => 'Prestações de serviço',
            '37.3.3' => 'Juros',
            '37.4' => 'Encargos a repartir por períodos futuros',
            '37.4.1' => 'Descontos de emissão de obrigações',
            '37.4.2' => 'Descontos de emissão de títulos de participação',
            '37.5' => 'Encargos a pagar',
            '37.5.1' => 'Remunerações',
            '37.5.2' => 'Juros',
            '37.6' => 'Proveitos a repartir por períodos futuros',
            '37.6.1' => 'Prémios de emissão de obrigações',
            '37.6.2' => 'Prémios de emissão de títulos de participação',
            '37.6.3' => 'Subsídios para investimento',
            '37.6.4' => 'Diferenças de câmbio favoráveis reversíveis',
            '37.7' => 'Contas transitórias',
            '37.7.1' => 'Transacções entre a sede e as dependências da empresa',
            '37.9' => 'Outros valores a receber e a pagar',
            '37.9.1' => 'Credores Diversos',
        ],
    ],
    '38' => [
        'nome' => 'Provisões para cobranças duvidosas',
        'subcontas' => [
            '38.1' => 'Provisões para clientes',
            '38.1.1' => 'Clientes – corrente',        
        ],
    ],
    '39' => [
        'nome' => 'Provisões para outros riscos e encargos',
        'subcontas' => [
            '39.1' => 'Provisões para pensões',
            '39.2' => 'Provisões para processos judiciais em curso',
            '39.3' => 'Provisões para acidentes de trabalho',
            '39.4' => 'Provisões para garantias dadas a clientes',
            '39.9' => 'Provisões para outros riscos e encargos',
        ],
    ],
    
    '41' => [
        'nome' => 'Títulos negociáveis',
        'subcontas' => [
            '41.1' => 'Acções',
            '41.1.1' => 'Empresas do grupo',
            '41.1.2' => 'Associadas',
            '41.1.3' => 'Outras empresas',
            '41.2' => 'Obrigações',
            '41.2.1' => 'Empresas do grupo',
            '41.2.2' => 'Associadas',
            '41.2.3' => 'Outras empresas',
            '41.3' => 'Títulos da dívida pública',
        ],
    ],
    '42' => [
        'nome' => 'Depósitos a prazo',
        'subcontas' => [
            '42.1' => 'Moeda nacional',
            '42.2' => 'Moeda estrangeira',
        ]
    ],
    '43' => [
        'nome' => 'Depósitos à ordém',
        'subcontas' => [
            '43.1' => 'Moeda nacional',
            '43.2' => 'Moeda estrangeira',
        ]
    ],
    '44' => [
        'nome' => 'Outros depósitos',
        'subcontas' => [
            '44.1' => 'Moeda nacional',
            '44.2' => 'Moeda estrangeira',
        ]
    ],
    '45' => [
        'nome' => 'Caixa',
        'subcontas' => [
            '45.1' => 'Fundo fixo',
            '45.2' => 'Valores para depositar',
            '45.3' => 'Valores destinados a pagamentos específicos',
        ]
    ],
    '48' => [
        'nome' => 'Conta transitória',
        'subcontas' => [],
    ],
    '49' => [
        'nome' => 'Provisões para aplicações de tesouraria',
        'subcontas' => [
            '49.1' => 'Títulos negociáveis',
            '49.1.1' => 'Acçõe',
            '49.1.2' => 'Obrigações',
            '49.1.3' => 'Títulos da dívida pública',
            '49.2' => 'Outras aplicações de tesouraria',
        ]
    ],
     

    '51' => [
        'nome' => 'Capital',
        'subcontas' => [
            '51.1' => 'Capital',
        ],
    ],
    '52' => [
        'nome' => 'Acções/quotas próprias',
        'subcontas' => [
            '52.1' => 'Valor nomina',
            '52.2' => 'Descontos',
            '52.3' => 'Prémios',
        ],
    ],
    '53' => [
        'nome' => 'Prémios de emissão',
        'subcontas' => [],
    ],
    '54' => [
        'nome' => 'Prestações suplementares',
        'subcontas' => [],
    ],
    '55' => [
        'nome' => 'Reservas legais',
        'subcontas' => [],
    ],
    '56' => [
        'nome' => 'Reservas de reavaliação',
        'subcontas' => [
            '56.1' => 'Legais',
            '56.1.1' => 'Decreto-Lei n.º ___',
            '56.1.2' => 'Decreto-Lei n.º ___',
            '56.2' => 'Autónomas',
            '56.2.1' => 'Avaliação',
        ],
    ],
    '57' => [
        'nome' => 'Reservas com fins especiais',
        'subcontas' => [
            '57.1' => 'Avaliação',
        ],
    ],
    

    '61' => [
        'nome' => 'Vendas',
        'subcontas' => [
            '61.1' => 'Produtos acabados e intermédios',      
            '61.1.1' => 'Mercado nacional',      
            '61.1.2' => 'Mercado estrangeiro',      
            '61.2' => 'Sub-produtos, desperdícios',      
            '61.2.1' => 'Mercado nacional',      
            '61.2.2' => 'Mercado estrangeiro',      
            '61.3' => 'Mercadorias',      
            '61.3.1' => 'Mercado nacional',      
            '61.3.2' => 'Mercado estrangeiro',      
            '61.4' => 'Embalagens de consumo',      
            '61.4.1' => 'Mercado nacional',      
            '61.4.2' => 'Mercado estrangeiro',      
            '61.5' => 'Subsídios a preços',      
            '61.7' => 'Devoluções',      
            '61.7.1' => 'Mercado nacional',      
            '61.7.2' => 'Mercado estrangeiro',      
            '61.8' => 'Descontos e abatimento',      
            '61.8.1' => 'Mercado nacional',      
            '61.8.2' => 'Mercado estrangeiro',      
            '61.9' => 'Transferência para resultados operacionais',
        ],
    ],
    '62' => [
        'nome' => 'Prestações de serviços',
        'subcontas' => [
            '62.1' => 'Serviços principais',      
            '62.1.1' => 'Mercado nacional',      
            '62.1.2' => 'Mercado estrangeiro',      
            '62.2' => 'Serviços secundários',      
            '62.2.1' => 'Mercado nacional"',      
            '62.2.2' => 'Mercado estrangeiro',      
            '62.8' => 'Descontos e abatimentos',      
            '62.8.1' => 'Mercado nacional',      
            '62.8.2' => 'Mercado estrangeiro',      
            '62.9' => 'Mercado estrangeiro',
        ],
    ],
    '63' => [
        'nome' => 'Outros proveitos operacionais',
        'subcontas' => [
            '63.1' => 'Serviços suplementares',      
            '63.1.1' => 'Aluguer de equipamento',      
            '63.1.2' => 'Cedência de pessoal',      
            '63.1.3' => 'Cedência de energia',      
            '63.1.4' => 'Estudos, projectos e assistência técnica',      
            '63.2' => 'Royalties',      
            '63.3' => 'Subsídios à exploração',      
            '63.4' => 'Subsídios a investimento',      
            '63.5' => 'IVA',      
            '63.8' => 'Outros proveitos e ganhos operacionais', 
        ],
    ],
    '64' => [
        'nome' => 'Variação nos inventários de produtos acabados e de produção em curso',
        'subcontas' => [
            '64.1' => 'Produtos e trabalhos em curso',      
            '64.2' => 'Produtos acabados',      
            '64.3' => 'Produtos intermédios',  
        ]
    ],
    '65' => [
        'nome' => 'Trabalhos para a própria empresa',
        'subcontas' => [
            '65.1' => 'Para imobilizado',      
            '65.1.1' => 'Corpóreo',      
            '65.1.2' => 'Incorpóreo',      
            '65.1.3' => 'Financeiro',      
            '65.1.4' => 'Em curso',      
            '65.2' => 'Para encargos a repartir por exercícios futuros',      
            '65.9' => 'Transferência para resultados operacionais',
        ]
    ],
    '66' => [
        'nome' => 'Proveitos e ganhos financeiros gerais',
        'subcontas' => [
            '66.1' => 'Juros',      
            '66.1.1' => 'De investimentos financeiros',      
            '66.1.1.1' => 'Obrigações',      
            '66.1.1.3' => 'Títulos de participação',      
            '66.1.1.4' => 'Empréstimos',      
            '66.1.1.9' => 'Outros',      
            '66.1.2' => 'De mora relativos a dívidas de terceiros',      
            '66.1.2.1' => 'Dívidas recebidas a prestações',      
            '66.1.2.2' => 'De empréstimos a terceiros',      
            '66.1.4' => 'Desconto de títulos',      
            '66.1.5' => 'De aplicações de tesouraria',      
            '66.2' => 'Diferenças de câmbio favoráveis',      
            '66.2.1' => 'Realizadas',      
            '66.2.2' => 'Não realizadas',      
            '66.3' => 'Descontos de pronto pagamento obtidos',      
            '66.4' => 'Rendimentos de investimentos em imóveis',      
            '66.5' => 'Rendimento de participações de capital',      
            '66.5.1' => 'Acções, quotas em outras empresas',      
            '66.5.2' => 'Acções, quotas incluídas nos fundos',      
            '66.5.3' => 'Acções, quotas incluídas nos títulos negociáveis',      
            '66.6' => 'Ganhos na alienação de aplicações financeiras',      
            '66.6.1' => 'Investimentos financeiros',      
            '66.6.1.1' => 'Subsidiárias',      
            '66.6.1.2' => 'Associadas',      
            '66.6.1.3' => 'Outras empresas',      
            '66.6.1.4' => 'Imóveis',      
            '66.6.1.5' => 'Fundos',      
            '66.6.1.9' => 'Outros investimentos',      
            '66.6.2' => 'Títulos negociáveis',      
            '66.7' => 'Reposição de provisões',      
            '66.7.1' => 'Investimentos financeiros',      
            '66.7.1.1' => 'Subsidiárias',      
            '66.7.1.2' => 'Associadas',      
            '66.7.1.3' => 'Outras empresas',      
            '66.7.1.4' => 'Fundos',      
            '66.7.1.9' => 'Outros investimentos',      
            '66.7.2' => 'Aplicações de tesouraria',      
            '66.7.2.1' => 'Títulos negociáveis',      
            '66.7.2.2' => 'Depósitos a prazo',      
            '66.7.2.3' => 'Outros depósitos',      
            '66.7.2.9' => 'Outros investimentos',      
            '66.9' => 'Transferência para resultados financeiros',
        ],
    ],
    '67' => [
        'nome' => 'Proveitos e ganhos financeiros em filiais e associadas',
        'subcontas' => [
            '67.1' => 'Rendimento de participações de capital',      
            '67.1.1' => 'Subsidiárias',      
            '67.1.2' => 'Associadas',      
            '67.9' => 'Transferência para resultados em filiais e associadas', 
        ]
    ],
    '68' => [
        'nome' => 'Outros proveitos não operacionais',
        'subcontas' => [
            '68.1.1' => 'Existências',      
            '68.1.1.1' => 'Matérias-primas subsidiárias e de consumo',      
            '68.1.1.2' => 'Produtos e trabalhos em curso',      
            '68.1.1.3' => 'Produtos acabados e intermédios',      
            '68.1.1.4' => 'Sub-produtos',      
            '68.1.1.5' => 'Mercadorias',      
            '68.1.2' => 'Cobranças duvidosas',      
            '68.1.2.1' => 'Clientes',      
            '68.1.2.2' => 'Clientes – títulos a receber',      
            '68.1.2.3' => 'Clientes – cobrança duvidosa',      
            '68.1.2.4' => 'Saldos devedores de fornecedores',      
            '68.1.2.5' => 'Participantes e participadas',      
            '68.1.2.6' => 'Dívidas do Pessoal',      
            '68.1.2.9' => 'Outros saldos a receber',      
            '68.1.3' => 'Riscos e encargos',      
            '68.1.3.1' => 'Pensões',      
            '68.1.3.2' => 'Processos judiciais em curso',      
            '68.1.3.3' => 'Acidentes de trabalho',      
            '68.1.3.4' => 'Garantias dadas a clientes',      
            '68.1.3.9' => 'Outros riscos e encargos',      
            '68.10' => 'Correcções relativas a exercícios anteriores',      
            '68.10.1' => 'Estimativa impostos',      
            '68.10.2' => 'Restituição de impostos',      
            '68.11' => 'Outros ganhos e perdas não operacionais',      
            '68.11.1' => 'Donativos',      
            '68.19' => 'Transferência para resultados não operacionais',      
            '68.2' => 'Anulação de amortizações extraordinárias',      
            '68.2.1' => 'Imobilizações corpóreas',      
            '68.2.2' => 'Imobilizações incorpóreas',      
            '68.3' => 'Ganhos em imobilizações',      
            '68.3.1' => 'Venda de imobilizações corpóreas',      
            '68.3.2' => 'Venda de imobilizações incorpóreas',      
            '68.4' => 'Ganhos em existências',      
            '68.4.1' => 'Sobras',      
            '68.5' => 'Recuperação de dívidas',      
            '68.6' => 'Benefícios de penalidades contratuais',      
            '68.8' => 'Descontinuidade de operações',      
            '68.9' => 'Alterações de políticas contabilísticas',
        ]
    ]
    '69' => [
        'nome' => 'Proveitos e ganhos extraordinários',
        'subcontas' => [
            '69.1' => 'Ganhos resultantes de catástrofes naturais',      
            '69.2' => 'Ganhos resultantes de convulsões políticas',      
            '69.3' => 'Ganhos resultantes de expropriações',      
            '69.4' => 'Ganhos resultantes de sinistros',      
            '69.5' => 'Subsídios',
            '69.6' => 'Anulação de passivos não exigíveis',
            '69.9' => 'Transferência para resultados extraordinários',   
        ],
    ],

    '71' => [
        'nome' => 'Custo das mercadorias vendidas e das matérias consumidas',
        'subcontas' => [
            '71.1' => 'Matérias-primas',
            '71.2' => 'Matérias subsidiárias',
            '71.3' => 'Materiais diversos',
            '71.4' => 'Embalagens de consumo',
            '71.5' => 'Outros materiais',
            '71.6' => 'Custos de Mercadorias Vendidas',
            '71.9' => 'Transferência para resultados operacionais',
        ],
    ],
    '72' => [
        'nome' => 'Custos com o pessoal',
        'subcontas' => [
            '72.1' => 'Remunerações – Órgãos sociais',
            '72.2' => 'Remunerações – Pessoal',
            '72.3' => 'Pensões',
            '72.3.1' => 'Órgãos sociais',
            '72.3.2' => 'Pessoal',
            '72.4' => 'Prémios para pensões',
            '72.4.1' => 'Órgãos sociais',
            '72.4.2' => 'Pessoal',
            '72.5' => 'Encargos sobre remunerações',
            '72.5.1' => 'Órgãos sociais',
            '72.5.2' => 'Pessoal',
        ],
    ],
    '73' => [
        'nome' => 'Amortizações do exercício',
        'subcontas' => [
            '73.1' => 'Imobilizações corpóreas',
            '73.1.2' => 'Edifícios e outras construções',
            '73.1.3' => 'Equipamento básico',
            '73.1.4' => 'Equipamento de carga e transporte',
            '73.1.5' => 'Equipamento administrativo',
            '73.1.6' => 'Taras e vasilhame',
            '73.1.9' => 'Outras imobilizações corpóreas',
            '73.2' => 'Imobilizações incorpóreas',
            '73.2.1' => 'Trespasses',
            '73.2.2' => 'Despesas de investigação e desenvolvimento',
            '73.2.3' => 'Propriedade industrial e outros direitos e contratos',
            '73.2.4' => 'Despesas de constituição',
            '73.2.9' => 'Outras imobilizações incorpóreas',
            '73.9' => 'Transferência para resultados operacionais',
        ],
    ],
    '75' => [
        'nome' => 'Outros custos e perdas operacionais',
        'subcontas' => [
            '75.1' => 'Sub-contratos',
            '75.2' => 'Fomecimentos e serviços de terceiros',
            '75.2.11' => 'Água',
            '75.2.12' => 'Electricidade',
            '75.2.13' => 'Combustíveis e outros fluídos',
            '75.2.14' => 'Conservação e reparação',
            '75.2.15' => 'Material de protecção segurança e conforto',
            '75.2.16' => 'Ferramentas e utensílios de desgaste rápido',
            '75.2.17' => 'Material de escritório',
            '75.2.18' => 'Livros e documentação técnica',
            '75.2.19' => 'Outros fornecimentos',
            '75.2.20' => 'Comunicação',
            '75.2.21' => 'Rendas e alugueres',
            '75.2.22' => 'Seguros',
            '75.2.23' => 'Deslocações e estadas',
            '75.2.24' => 'Despesas de representação',
            '75.2.26' => 'Conservação e reparação',
            '75.2.27' => 'Vigilância e segurança',
            '75.2.28' => 'Limpeza, higiene e conforto',
            '75.2.29' => 'Publicidade e propaganda',
            '75.2.30' => 'Contencioso e notariado',
            '75.2.31' => 'Comissões a intermediários',
            '75.2.32' => 'Assistência técnica',
            '75.2.32.1' => 'Estrangeira',
            '75.2.32.2' => 'Nacional',
            '75.2.33' => 'Trabalhos executados no exterior',
            '75.2.34' => 'Honorários e avenças',
            '75.2.35' => 'Royalties',
            '75.2.39' => 'Outros serviços',
            '75.3' => 'Impostos',
            '75.3.1' => 'Indirectos',
            '75.3.1.1' => 'Imposto de selo',
            '75.3.1.2' => 'IVA',
            '75.3.1.9' => 'Outros impostos',
            '75.3.2' => 'Directos',
            '75.3.2.1' => 'Imposto de capitais',
            '75.3.2.2' => 'Contribuição predial',
            '75.3.2.9' => 'Outros impostos',
            '75.4' => 'Despesas confidênciais',
            '75.5' => 'Quotizações',
            '75.6' => 'Ofertas e Amostras de existências',
            '75.8' => 'Outros custos e perdas operacionais',
            '75.9' => 'Transferências para resultados operacionais',
        ],
    ],
    '76' => [
        'nome' => 'Custos e perdas financeiros gerais',
        'subcontas' = [
            '76.1' => 'Juros',
            '76.1.1' => 'De empréstimos',
            '76.1.1.1' => 'Bancários',
            '76.1.1.2' => 'Obrigações',
            '76.1.1.3' => 'Títulos de participação',
            '76.1.2' => 'De descobertos bancários',
            '76.1.3' => 'De mora relativos a dívidas a terceiros',
            '76.1.4' => 'De desconto de títulos',
            '76.2' => 'Diferenças de câmbio desfavoráveis',
            '76.2.1' => 'Realizadas',
            '76.3' => 'Descontos de pronto pagamento concedidos',
            '76.4' => 'Amortizações de investimentos em imóveis',
            '76.5' => 'Provisões para aplicações financeiras',
            '76.5.1' => 'Investimentos financeiros',
            '76.5.1.1' => 'Subsidiárias',
            '76.5.1.2' => 'Associadas',
            '76.5.1.3' => 'Outras empresas',
            '76.5.1.4' => 'Fundos',
            '76.5.1.9' => 'Outros investimentos',
            '76.5.2' => 'Aplicações de tesouraria',
            '76.5.2.1' => 'Títulos negociáveis',
            '76.5.2.2' => 'Depósitos a prazo',
            '76.5.2.3' => 'Outros depósitos',
            '76.5.2.9' => 'Outros',
            '76.6' => 'Perdas na alienação de aplicações financeiras',
            '76.6.1' => 'Investimentos financeiros',
            '76.6.1.1' => 'Subsidiárias',
            '76.6.1.2' => 'Associadas',
            '76.6.1.3' => 'Outras empresas',
            '76.6.1.9' => 'Outros investimentos',
            '76.6.2' => 'Aplicações de títulos negociáveis',
            '76.7' => 'Serviços bancários',
            '76.9' => 'Transferência para resultados financeiros',
        ],
    ],
    '77' => [
        'nome' => 'Custos e perdas financeiros em filiais e associadas',
        'subcontas' => [
            '77.9' => 'Transferência para resultados financeiros',
        ],
    ],
    '78' => [
        'nome' => 'Outros custos e perdas não operacionais',
        'subcontas' => [
            '78.1' => 'Provisões do exercício',
            '78.1.1' => 'Existências',
            '78.1.1.1' => 'Matérias-primas subsidiárias e de consumo',
            '78.1.1.2' => 'Produtos e trabalhos em curso',
            '78.1.1.3' => 'Produtos acabados e intermédios',
            '78.1.1.4' => 'Sub-produtos, desperdícios, resíduos e refugos',
            '78.1.1.5' => 'Mercadorias',
            '78.1.2' => 'Cobranças Duvidosas',
            '78.1.2.1' => 'Clientes',
            '78.1.2.2' => 'Clientes – títulos a receber',
            '78.1.2.3' => 'Clientes – cobrança duvidosa',
            '78.1.2.4' => 'Saldos devedores de fornecedores',
            '78.1.2.5' => 'Participantes e participadas',
            '78.1.2.6' => 'Dívidas do pessoal',
            '78.1.2.9' => 'Outros saldos a receber',
            '78.1.3' => 'Riscos e encargos',
            '78.1.3.1' => 'Pensões',
            '78.1.3.2' => 'Processos judiciais em curso',
            '78.1.3.3' => 'Acidentes de trabalho',
            '78.1.3.4' => 'Garantias dadas a clientes',
            '78.1.3.9' => 'Outros riscos e encargos',
            '78.2' => 'Amortizações extraordinárias',
            '78.2.1' => 'Imobilizações Corpóreas',
            '78.2.2' => 'Imobilizações Incorpóreas',
            '78.3' => 'Perdas em imobilizações',
            '78.3.1' => 'Venda de imobilizações corpóreas',
            '78.3.2' => 'Venda de imobilizações incorpóreas',
            '78.3.3' => 'Abates',
            '78.3.9' => 'Outras',
            '78.4' => 'Perdas em existências',
            '78.4.1' => 'Quebras',
            '78.5' => 'Dívidas incobráveis',
            '78.6' => 'Multas e penalidades contratuais',
            '78.6.1' => 'Fiscais',
            '78.6.2' => 'Não fiscais',
            '78.6.3' => 'Penalidades contratuais',
            '78.7' => 'Custos de reestruturação',
            '78.8' => 'Descontinuidade de operações',
            '78.9' => 'Alterações de políticas contabilísticas',
            '78.10' => 'Correcções relativas a exercícios anteriores',
            '78.10.1' => 'Estimativa impostos',
            '78.11' => 'Outros custos e perdas não operacionais',
            '78.11.1' => 'Donativos',
            '78.11.2' => 'Reembolso de subsídios à exploração',
            '78.11.3' => 'Reembolso de subsídios a investimentos',
            '78.19' => 'Transferência para resultados não operacionais',
        ],
    ],
    '79' = > [
        'nome' => 'Custos e perdas extraordinárias',
        'subcontas' => [
            '79.1' => 'Perdas resultantes de catástrofes naturais',
            '79.2' => 'Perdas resultantes de convulsões políticas',
            '79.3' => 'Perdas resultantes de expropriações',
            '79.4' => 'Perdas resultantes de sinistros',
            '79.9' => 'Transferência para resultados extraordinários',
        ],
    ],
    
    '81' => [
        'nome' => 'Resultados transitados',
        'subcontas' => [
            '81.1' => 'Ano',
            '81.1.1' => 'Resultado do ano',
            '81.1.2' => 'Aplicação de resultados',
            '81.1.3' => 'Correcções de erros fundamentais, no exercício seguinte',
            '81.1.4' => 'Efeito das alterações de políticas contabilísticas',
            '81.1.5' => 'Imposto relativo a correcções de erros fundamentais e alterações de políticas contabilísticas',
            '81.2' => 'Ano',
            '81.2.1' => 'Resultado do ano',
            '81.2.2' => 'Aplicação de resultados',
            '81.2.3' => 'Correcções de erros fundamentais, no exercício seguinte',
            '81.2.4' => 'Efeito das alterações de políticas contabilísticas',
            '81.2.5' => 'Imposto relativo a correcções de erros fundamentais e alterações de políticas contabilísticas',
        ],
    ],
    '82' => [
        'nome' => 'Resultados operacionais',
        'subcontas' => [
            '82.1' => 'Vendas',
            '82.2' => 'Prestações de serviço',
            '82.3' => 'Outros proveitos operacionais',
            '82.4' => 'Variação nos inventários de produtos acabados e produtos em vias de fabrico',
            '82.5' => 'Trabalhos para a própria empresa',
            '82.6' => 'Custo das mercadorias vendidas e das matérias consumidas',
            '82.7' => 'Custos com o pessoal',
            '82.8' => 'Amortizações do exercício',
            '82.9' => 'Outros custos operacionais',
            '82.19' => 'Transferência para resultados líquidos',
        ]
    ],
    '83' => [
        'nome' => 'Resultados financeiros',
        'subcontas' => [
            '83.1' => 'Proveitos e ganhos financeiros gerais',
            '83.2' => 'Custos e perdas financeiros gerais',
            '83.9' => 'Transferência para resultados líquidos',
        ],
    ],
    '84' => [
        'nome' => 'Resultados em filiais e associadas',
        'subcontas' => [
            '84.1' => 'Proveitos e ganhos em filiais e associadas',
            '84.2' => 'Custos e perdas em filiais e associadas',
            '84.9' => 'Transferência para resultados líquidos',
        ],
    ],
    '85' => [
        'nome' => 'Resultados não operacionais',
        'subcontas' => [
            '85.1' => 'Proveitos e ganhos não operacionais',
            '85.2' => 'Custos e perdas não operacionais',
            '85.9' => 'Transferência para resultados líquidos',
        ],
    ],
    '86' => [
        'nome' => 'Resultados extraordinários',
        'subcontas' => [
            '86.1' => 'Proveitos e ganhos extraordinários',
            '86.2' => 'Custos e perdas extraordinários',
            '86.9' => 'Transferência para resultados líquidos',
        ],
    ],
    '87' => [
        'nome' => 'Imposto sobre os lucros',
        'subcontas' => [
            '87.1' => 'Imposto sobre os resultados correntes',
            '87.2' => 'Imposto sobre os resultados extraordinários',
            '87.9' => 'Transferência para resultados líquidos',
        ],
    ],
    '88' => [
        'nome' => 'Resultado líquido do exercício',
        'subcontas' => [
            '88.1' => 'Resultados operacionais',
            '88.2' => 'Resultados financeiros gerais',
            '88.3' => 'Resultados em filiais e associadas',
            '88.4' => 'Resultados não operacionais',
            '88.5' => 'Imposto sobre os resultados correntes',
            '88.6' => 'Resultados extraordinários',
            '88.7' => 'Imposto sobre os resultados extraordinários',
        ],
    ],
    '89' => [
        'nome' => 'Dividendos antecipados',
        'subcontas' => [
            '88.9' => 'Transferência para resultados transitados',
            '89.9' => 'Transferência para resultados transitados',
        ],
    ],
];
