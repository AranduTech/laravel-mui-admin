<?php

namespace Arandu\LaravelMuiAdmin\Services;

use Arandu\LaravelMuiAdmin\Contracts\Dashboard;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class DashboardService
{

    /** @var \Illuminate\Support\Collection<Dashboard> */
    private $dashboards;


    public function __construct()
    {
        $this->dashboards = collect();

        $dir = app_path(config('admin.bi.dashboards_namespace', 'Dashboards'));
        $namespace = app()->getNamespace();

        foreach ((new Finder())->in($dir)->files() as $dashboard) {
            $dashboard = $namespace . str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($dashboard->getPathname(), app_path() . DIRECTORY_SEPARATOR)
            );

            if (is_subclass_of($dashboard, Dashboard::class) && ! (new \ReflectionClass($dashboard))->isAbstract()) {
                $this->dashboards->push(App::make($dashboard));
            }
        }

    }

    public function all(): \Illuminate\Support\Collection
    {
        return $this->dashboards;
    }

    public function find($uri): ?Dashboard
    {
        return $this->dashboards->firstWhere('uri', $uri);
    }

    public function first(): ?Dashboard
    {
        return $this->dashboards->first();
    }


    public function registerApi()
    {
        Route::get('dashboard/{dashboard}/widgets', 'DashboardController@dashboard')->name('admin.bi');
        Route::get('dashboard/{dashboard}/widget/{widget}/data', 'DashboardController@widget')->name('admin.bi.data');
        Route::get('dashboard/{dashboard}/export', 'DashboardController@export')->name('admin.bi.export');
    }
}