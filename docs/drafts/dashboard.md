
# Arandu\LaravelMuiAdmin Dashboard API Documentation

## Introduction

Welcome to the `Arandu\LaravelMuiAdmin` Dashboard API documentation. This guide provides an overview of how to create and customize dashboards using this package. The API is designed to integrate seamlessly with Laravel and React, offering a flexible and powerful tool for building admin panels.

## Creating a Dashboard

A dashboard in `Arandu\LaravelMuiAdmin` is a class extending the `Dashboard` contract. It represents a collection of widgets organized to display data effectively. Let's create a simple dashboard:

### Example: `EmployeeDashboard`

```php
<?php

namespace App\Dashboards;

use Arandu\LaravelMuiAdmin\Contracts\Dashboard;
use App\Models\Employee; // Example model

class EmployeeDashboard extends Dashboard
{
    protected $uri = 'employees';
    protected $title = 'Employee Overview';
    protected $model = Employee::class;

    public function widgets(): array
    {
        return [
            // Widgets will be defined here
        ];
    }
}
```

This dashboard is centered around the `Employee` model (an illustrative example). You can replace `Employee` with any model you wish to display. The model will be used to fetch data for the widgets.

## Widgets

Widgets are the building blocks of your dashboard. Each widget can display data in various formats like charts, tables, or key performance indicators (KPIs).

### Creating a Widget

Here's an example of creating a simple widget:

```php
Widget::create('Employee Count')
    ->withValues(CountMetric::create('id', 'Employees'))
    ->withLayout(['type' => 'kpi']),
```

This widget displays the total count of employees.

### Adding Metrics and Dimensions

Metrics and dimensions add depth to your widgets. Metrics are quantitative measurements, while dimensions are qualitative attributes.

#### Example: Average Ticket per Day

In the following example, let'assume the dashboard is associated with a `Order` model. The `Order` model has a `value` column that stores the value of each order. To display the average value of orders per day, we can use the `AverageMetric` class:

```php
Widget::create('Average Ticket per Day')
    ->withValues(AverageMetric::create('value', 'Average Ticket'))
    ->withXAxis(DateDimension::create('created_at', 'Day'))
    ->withLayout([
        'type' => ['line', 'table']
    ]),
```

The `AverageMetric` class calculates the average value of orders. The `DateDimension` class groups the orders by day, removes the time component, and is displayed on the X-axis.

## Adding a Scope

You can add a scope to a widget to filter the data fetched from the model. The scope is a callback function that receives the query builder instance as an argument. You can use this callback to add any additional filters to the query.

For example, if you have an `UsersDashboard` and there is one widget that you want to display data from the users with the `developer` role, you can do it like this:

```php
Widget::create('Developers Widget')
    ->withScope(function ($query) {
        $query->role('developer');
    })
    // ... continue with the rest of the widget
```

## Adding a custom dataset

You can also add a custom dataset to a widget. When you add a custom dataset, the widget will not fetch data from the model. Instead, it will use the dataset you provide. Therefor, you could use this feature to display data from an external API, or from a different model.

### Example: 

```php

Widget::create('A custom dataset widget')
    ->withDataset(function ($query) {
        return [
            [
                'country' => 'Brazil',
                'GDP' => 45.30,
            ],
            [
                'country' => 'USA',
                'GDP' => 20.93,
            ],
            [
                'country' => 'China',
                'GDP' => 14.34,
            ],
        ];
    })
    ->withLayout(['type' => 'pie'])
    ->withValues(SumMetric::create('GDP', 'GDP'))
    ->withGroups(TextDimension::create('country', 'Country')),
```



```php
<?php

namespace App\Dashboards;

use Arandu\LaravelMuiAdmin\Contracts\Dashboard;

class TimesheetsDashboard extends Dashboard
{

    protected $uri = 'timesheets';

    protected $title = 'Apontamentos';

    protected $model = Timesheet::class;

    public function widgets(): array
    {
        return [
            Widget::create('Horas Totais')
                ->withUri('total_hours')
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
            
            Widget::create('Horas Trabalhadas por Usuário por dia')
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

            Widget::create('Número de desenvolvedores')
                ->withDataset(function ($query) {
                    return User::role('developer')->count();
                })
                ->withLayout([
                    'grid' => ['xs' => 12, 'md' => 6, 'lg' => 4],
                    'type' => 'kpi',
                ]);


        ];
    }
}

```

