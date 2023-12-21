# Painéis (Dashboards)

## Introdução

Bem-vindo à documentação da API do Painel `Arandu\LaravelMuiAdmin`. Este guia oferece uma visão geral de como criar e personalizar painéis usando este pacote. A API é projetada para integrar-se perfeitamente com Laravel e React, oferecendo uma ferramenta flexível e poderosa para construir painéis de administração.

## Criando um Painel

Um painel no `Arandu\LaravelMuiAdmin` é uma classe que estende o contrato `Dashboard`. Ele representa uma coleção de widgets organizados para exibir dados de forma eficaz. Vamos criar um painel simples:

### Exemplo: `EmployeeDashboard`

```php
<?php

namespace App\Dashboards;

use Arandu\LaravelMuiAdmin\Contracts\Dashboard;
use App\Models\Employee; // Modelo de exemplo

class EmployeeDashboard extends Dashboard
{
    protected $uri = 'employees';
    protected $title = 'Visão Geral do Empregado';
    protected $model = Employee::class;

    public function widgets(): array
    {
        return [
            // Os widgets serão definidos aqui
        ];
    }
}
```

Este painel é centrado no modelo `Employee`. Você pode substituir `Employee` por qualquer modelo que deseje exibir. O modelo será usado para buscar dados para os widgets.

## Widgets

Widgets são os blocos de construção do seu painel. Cada widget pode exibir dados em vários formatos, como gráficos, tabelas ou indicadores-chave de desempenho (KPIs). A partir do frontend também é possível criar widgets personalizados.

### Criando um Widget

Aqui está um exemplo de criação de um widget simples:

```php
Widget::create('Contagem de Empregados')
    ->withValues(CountMetric::create('id', 'Empregados'))
    ->withLayout(['type' => 'kpi']),
```

Este widget exibe a contagem total de empregados.

### Adicionando Métricas e Dimensões

Métricas e dimensões adicionam profundidade aos seus widgets. Métricas são medidas quantitativas, enquanto dimensões são atributos qualitativos.

#### Exemplo: Ticket Médio por Dia

No exemplo a seguir, vamos assumir que o painel está associado a um modelo `Order`. O modelo `Order` tem uma coluna `value` que armazena o valor de cada pedido. Para exibir o valor médio dos pedidos por dia, podemos usar a métrica `AverageMetric`:

```php
Widget::create('Ticket Médio por Dia')
    ->withValues(AverageMetric::create('value', 'Ticket Médio'))
    ->withXAxis(DateDimension::create('created_at', 'Dia'))
    ->withLayout([
        'type' => ['line', 'table']
    ]),
```

A classe `AverageMetric` calcula o valor médio dos pedidos. A classe `DateDimension`  remove o componente de tempo, agrupa os pedidos por dia e é exibida no eixo X.

## Adicionando um Escopo

Você pode adicionar um escopo a um widget para filtrar os dados buscados do modelo. O escopo é uma função que recebe a instância do construtor de consultas como argumento. Você pode usar essa função para adicionar quaisquer filtros adicionais à consulta.

Por exemplo, se você tem um `UsersDashboard` e há um widget que você deseja exibir dados dos usuários com o papel de `developer`, você pode fazer isso assim:

```php
Widget::create('Widget de Desenvolvedores')
    ->withScope(function ($query) {
        $query->role('developer');
    })
    // ... continue com o restante do widget
```

## Adicionando um Conjunto de Dados Personalizado

Você também pode adicionar um conjunto de dados personalizado a um widget. Quando você adiciona um conjunto de dados personalizado, o widget não buscará dados do modelo. Em vez disso, usará o conjunto de dados que você fornecer. Portanto, você pode usar esse recurso, dentre outros, para: 
 - exibir dados de uma API externa 
 - exibir dados de um modelo diferente
 - construir uma consulta que sem utilizar as métricas e dimensões

### Exemplo:

```php
Widget::create('Widget de um conjunto de dados personalizado')
    ->withDataset(function ($query) {
        return [
            [
                'country' => 'Brasil',
                'GDP' => 45.30,
            ],
            [
                'country' => 'EUA',
                'GDP' => 20.93,
            ],
            [
                'country' => 'China',
                'GDP' => 14.34,
            ],
        ];
    })
    ->withLayout(['type' => ['pie', 'table']])
    ->withValues(SumMetric::create('GDP', 'PIB'))
    ->withGroups(TextDimension::create('country', 'País')),
```

Note que nesse caso, as métricas e dimensões serão utilizadas apenas para exibir os dados, mas não para construir a consulta.

## Frontend

O `Arandu\LaravelMuiAdmin` vem com o frontend React `@arandu/laravel-mui-admin`. Você já deve
```bash

