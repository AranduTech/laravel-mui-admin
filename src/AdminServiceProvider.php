<?php

namespace Arandu\LaravelMuiAdmin;

use Arandu\LaravelMuiAdmin\Commands\RoleAndPermissions;
use Arandu\LaravelMuiAdmin\Commands\CredentialsCommand;
use Arandu\LaravelMuiAdmin\Commands\MakeReactComponent;
use Arandu\LaravelMuiAdmin\Commands\ManifestCommand;
use Arandu\LaravelMuiAdmin\Services\AdminService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Laravel\Ui\UiCommand;

class AdminServiceProvider extends ServiceProvider
{
    public function boot(AdminService $adminService)
    {
        // $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        // $this->loadViewsFrom(__DIR__.'/resources/views', 'admin');
        // $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->publishes([
            __DIR__.'/../config/admin.php' => config_path('admin.php'),
        ]);

        UiCommand::macro('mui', function ($command) {
            Presets\Mui::install();

            $command->info('Mui scaffolding installed successfully.');
            $command->comment('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
        });

        Blueprint::macro('tracksChanges', function () {
            $this->unsignedBigInteger('created_by')->nullable()->before('created_at');
            $this->unsignedBigInteger('updated_by')->nullable()->before('updated_at');
        });

        Blueprint::macro('dropTracksChanges', function () {
            $this->dropColumn('created_by');
            $this->dropColumn('updated_by');
        });

        $models = $adminService->getModelsWithCrudSupport();

        $enforceMorphMap = [];

        foreach ($models as $model) {
            $instance = new $model();
            $enforceMorphMap[$instance->getSchemaName()] = \Illuminate\Support\Str::replaceFirst('\\', '', $model);
        }

        Relation::enforceMorphMap($enforceMorphMap);
    }

    public function register()
    {
        //
        $this->app->singleton('admin', function ($app) {
            return new AdminService();
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                CredentialsCommand::class,
                RoleAndPermissions::class,
                MakeReactComponent::class,
                ManifestCommand::class,
            ]);
        }
    }
}
