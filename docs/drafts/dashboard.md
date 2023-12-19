

```php
class TimesheetsDashboard extends Dashboard
{

    protected $title = 'Apontamentos';

    protected $model = Timesheet::class;

    public function widgets(): array
    {
        return [
            Widget::create('Horas Totais')
                ->identifiedBy('total_hours')
                ->withValues(SumMetric::create('work_time', 'Horas Trabalhadas'))
                ->withLayout([
                    'grid' => ['xs' => 12, 'md' => 6, 'lg' => 4],
                    'type' => 'kpi',
                ]),
                // Timesheet::query()->get([DB::raw('SUM(work_time) as sum_work_time')]);

            Widget::create('Horas nos últimos 7 dias')
                ->withValues(SumMetric::create('work_time', 'Horas Trabalhadas'))
                ->withScope(function ($query) {
                    $query->where('started_at', '>=', now()->subDays(7));
                })
                ->withLayout([
                    'grid' => ['xs' => 12, 'md' => 6, 'lg' => 4],
                    'type' => 'kpi',
                ]),
                // Timesheet::query()->where('started_at', '>=', now()->subDays(7))->get([DB::raw('SUM(work_time) as sum_work_time')]);

            Widget::create('Média de Horas por registro por Dia')
                ->withValues(AverageMetric::create('work_time', 'Horas Trabalhadas'))
                ->withXAxis(DateDimension::create('started_at', 'Dia'))
                ->withLayout([
                    'grid' => ['xs' => 12, 'md' => 6, 'lg' => 4],
                    'type' => ['line', 'bars'],
                ]),
                // Timesheet::query()->groupBy('date_started_at')->get([DB::raw('DATE(started_at) as date_started_at'), DB::raw('AVG(work_time) as average_work_time')]);

            Widget::create('Horas Trabalhadas por Dia')
                ->withXAxis(DateDimension::create('started_at', 'Dia'))
                ->withValues(SumMetric::create('work_time', 'Horas Trabalhadas'))
                ->withLayout([
                    'grid' => ['xs' => 12, 'md' => 6, 'lg' => 4],
                    'type' => ['line', 'bars'],
                ]),
                // Timesheet::query()->groupBy('date_started_at')->get([DB::raw('DATE(started_at) as date_started_at'), DB::raw('SUM(work_time) as sum_work_time')]);
            
            Widget::create('Horas Trabalhadas por Usuário')
                ->withXAxis(DateDimension::create('started_at', 'Dia'))
                ->withGroups(BelongsToDimension::create('user', 'Usuário'))
                ->withValues(SumMetric::create('work_time', 'Horas Trabalhadas'))
                ->withLayout([
                    'grid' => ['xs' => 12, 'md' => 6, 'lg' => 4],
                    'type' => ['line', 'line:stacked', 'bars:stacked'],
                ]),
                // Timesheet::query()->with('user')->groupBy('date_started_at', 'user_id')->get([DB::raw('DATE(started_at) as date_started_at'), 'user_id', DB::raw('SUM(work_time) as sum_work_time')]);
            
            Widget::create('Horas Trabalhadas por Projeto')
                ->withXAxis(DateDimension::create('started_at', 'Dia'))
                ->withGroups(BelongsToDimension::create('project', 'Projeto'))
                ->withValues(SumMetric::create('work_time', 'Horas Trabalhadas'))
                ->withLayout([
                    'grid' => ['xs' => 12, 'md' => 6, 'lg' => 4],
                    'type' => ['bars:stacked', 'pie'],
                ]),
                // Timesheet::query()->with('project')->groupBy('date_started_at', 'project_id')->get([DB::raw('DATE(started_at) as date_started_at'), 'project_id', DB::raw('SUM(work_time) as sum_work_time')]);

            Widget::create('Número de apontamentos por dia')
                ->withXAxis(DateDimension::create('started_at', 'Dia'))
                ->withValues(CountMetric::create('id', 'Número de apontamentos'))
                ->withLayout([
                    'grid' => ['xs' => 12, 'md' => 6, 'lg' => 4],
                    'type' => ['line', 'bars'],
                ]),
                // Timesheet::query()->groupBy('date_started_at')->get([DB::raw('DATE(started_at) as date_started_at'), DB::raw('COUNT(id) as count_id')]);


        ];
    }
}

```

