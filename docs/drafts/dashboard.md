

```php
class TimesheetsDashboard extends Dashboard
{

    protected $title = 'Apontamentos';

    protected $model = Timesheet::class;

    // should match TimesheetTable::$filterClass 
    // to be compatible with scopeWhereMatchesFilter
    // public function filter(): array
    // {
    //     return [
    //         [
    //             'name' => 'users',
    //             'title' => 'Usuários',
    //             'type' => 'autocomplete',
    //             'list' => 'user?role=developer',
    //         ],
    //         [
    //             'name' => 'projects',
    //             'title' => 'Projetos',
    //             'type' => 'autocomplete',
    //             'list' => 'project',
    //         ],
    //         [
    //             'name' => 'from',
    //             'title' => 'Data Inicial',
    //             'type' => 'date',
    //         ],
    //         [
    //             'name' => 'to',
    //             'title' => 'Data Final',
    //             'type' => 'date',
    //         ],
    //     ];
    // }

    public function widgets(): array
    {
        return [
            Widget::create('Horas Totais', 'bignumber')
                ->withMetric(SumMetric::create('work_time', 'Horas Trabalhadas'))
                ->withGrid(['xs' => 12, 'md' => 6, 'lg' => 4]),

            Widget::create('Horas nos últimos 7 dias', 'bignumber')
                ->withMetric(SumMetric::create('work_time', 'Horas Trabalhadas'))
                ->withScope(function ($query) {
                    $query->where('started_at', '>=', now()->subDays(7));
                })
                ->withGrid(['xs' => 12, 'md' => 6, 'lg' => 4]),

            Widget::create('Média de Horas por Dia', 'bignumber')
                ->withMetric(AverageMetric::create('work_time', 'Horas Trabalhadas'))
                ->withGroups(DateDimension::create('started_at', 'Dia'))
                ->withGrid(['xs' => 12, 'md' => 6, 'lg' => 4]),

            Widget::create('Horas Trabalhadas por Dia', 'line')
                ->withXAxis(DateDimension::create('started_at', 'Dia'))
                ->withMetric(SumMetric::create('work_time', 'Horas Trabalhadas'))
                ->withGrid(['xs' => 12, 'md' => 6, 'lg' => 4]),
            
            Widget::create('Horas Trabalhadas por Usuário', 'bar')
                ->withXAxis(DateDimension::create('started_at', 'Dia'))
                ->withGroups(BelongsToDimension::create('user', 'Usuário'))
                ->withMetric(SumMetric::create('work_time', 'Horas Trabalhadas'))
                ->withGrid(['xs' => 12, 'md' => 6, 'lg' => 4]),
            
            Widget::create('Horas Trabalhadas por Projeto', 'pie')
                ->withGroups(BelongsToDimension::create('project', 'Projeto'))
                ->withMetric(SumMetric::create('work_time', 'Horas Trabalhadas'))
                ->withGrid(['xs' => 12, 'md' => 6, 'lg' => 4]),

            


        ];
    }
}

```

