<?php

namespace Arandu\LaravelMuiAdmin;

use Arandu\LaravelMuiAdmin\Commands\RoleAndPermissions;
use Arandu\LaravelMuiAdmin\Commands\CredentialsCommand;
use Arandu\LaravelMuiAdmin\Services\AdminService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Laravel\Ui\UiCommand;

class AdminServiceProvider extends ServiceProvider
{
    public function boot()
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
            $this->unsignedBigInteger('created_by')->nullable();
            $this->unsignedBigInteger('updated_by')->nullable();
        });

        Blueprint::macro('dropTracksChanges', function () {
            $this->dropColumn('created_by');
            $this->dropColumn('updated_by');
        });
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
            ]);
        }
    }
}
