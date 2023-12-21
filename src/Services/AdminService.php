<?php

namespace Arandu\LaravelMuiAdmin\Services;

use App\Providers\RouteServiceProvider;
use Arandu\LaravelMuiAdmin\Facades\Dashboard;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Arandu\LaravelMuiAdmin\Http\Controllers\InitController;
use Arandu\LaravelMuiAdmin\Http\Controllers\RendererController;
use Arandu\LaravelMuiAdmin\Http\Controllers\RepositoryController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Arandu\LaravelMuiAdmin\Traits\HasAdminSupport;
use Illuminate\Support\Facades\Cache;

class AdminService
{
    private $models;
    private $routes = [];

    private $ignoreRoutes = [
        'ignition.healthCheck',
        'ignition.executeSolution',
        'ignition.shareReport',
        'ignition.scripts',
        'ignition.styles',
    ];

    private $guestRoutes = [
        'login',
        'register',
        'password.request',
        'password.email',
        'password.reset',
        'password.update',
        'home',
    ];


    public function __construct()
    {

        // Creates a list of routes for the React app to use
        $routeCollection = Route::getRoutes()->getRoutesByName();

        foreach ($routeCollection as $name => $route) {
            if (in_array($name, $this->ignoreRoutes)) {
                continue;
            }

            if (!app()->runningInConsole() && !auth()->user() && !in_array($name, $this->guestRoutes)) {
                continue;
            }

            $this->routes[$name] = $route->uri();
        }
    }


    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Cria as rotas para as models que implementam o contrato HasAdminSupport.
     *
     * @param array $options - Opções de configuração para ocultar ou exibir as rotas
     */
    public function web($options = [])
    {
        if (!isset($options['home']) || false !== $options['home']) {
            Route::get(RouteServiceProvider::HOME, [RendererController::class, 'render'])
                ->name('home');
        }
        $middleware = ['auth', 'verified'];
        if (isset($options['middleware'])) {
            $middleware = $options['middleware'];
        }
        if (!isset($options['profile']) || false !== $options['profile']) {
            Route::middleware(['auth'])
                ->get('/profile', [RendererController::class, 'render'])
                ->name('profile');
        }

        Route::group([
            'middleware' => $middleware,
        ], function () use ($options) {
            // Registra as rotas de CRUD para os modelos que implementam HasAdminSupport
            $models = $this->getModelsWithCrudSupport();
            foreach ($models as $model) {
                /** @var HasAdminSupport */
                $instance = new $model();

                $schemaName = $instance->getSchemaName();

                if (isset($options['include']) && !in_array($schemaName, $options['include'])) {
                    continue;
                }
                if (isset($options['exclude']) && in_array($schemaName, $options['exclude'])) {
                    continue;
                }
                // Registra as rotas para cada modelo
                $instance->web();
            }
        });
    }

    /**
     * Cria as rotas de API para as models que implementam o contrato HasAdminSupport.
     *
     * @param mixed $options
     */
    public function api($options = [])
    {

        Route::group([
            'namespace' => 'Arandu\LaravelMuiAdmin\Http\Controllers',
        ], function () {

            Route::group([
                'middleware' => config('admin.api.middleware', ['auth', 'verified']),
                'prefix' => config('admin.api.prefix', 'admin'),
            ], function () {
                Route::get('init', 'InitController@init');
    
                // Registra as rotas de CRUD para os modelos que implementam HasAdminSupport
                $models = $this->getModelsWithCrudSupport();
    
                foreach ($models as $model) {
                    $instance = new $model();
                    $instance->api();
                }
    
                Route::get('helpers/autocomplete', 'RepositoryController@autocomplete')
                    ->name('admin.autocomplete');
            });
    
            Route::group([
                'middleware' => config('admin.bi.api.middleware', ['auth', 'role:' . config('admin.roles.admin', 'admin')]),
                'prefix' => config('admin.bi.api.prefix', 'admin/bi'),
            ], function () {
                Dashboard::registerApi();
            });
        });

    }
    public function getModelSchema()
    {
        $callback = function () {
            $models = $this->getModelsWithCrudSupport();

            $schema = [];

            foreach ($models as $model) {
                $instance = new $model();
                $name = $instance->getSchemaName();
                $schema[$name] = $instance->getSchema();
            }

            return $schema;
        };

        if ($cacheKey = config('admin.cache.key', 'admin.cache') && config('admin.manifest', 'api') === 'api') {
            return Cache::remember(
                $cacheKey . '.models.schema', 
                config('admin.cache.ttl', 60), 
                $callback
            );
        }

        return $callback();
        
    }

    public function getModelsWithCrudSupport(): Collection
    {
        $callback = function () {
            /** @var mixed */
            $container = Container::getInstance();

            $models = collect(File::allFiles(app_path('Models')))
                ->map(function ($item) use ($container) {
                    $path = 'Models\\' . $item->getRelativePathName();

                    return sprintf(
                        '\%s%s',
                        $container->getNamespace(),
                        strtr(substr($path, 0, strrpos($path, '.')), DIRECTORY_SEPARATOR, '\\')
                    );
                })
                ->filter(function ($class) {
                    $valid = false;

                    if (class_exists($class)) {
                        $reflection = new \ReflectionClass($class);

                        $valid = $reflection->isSubclassOf(Model::class)
                            && !$reflection->isAbstract()
                            && in_array(HasAdminSupport::class, array_keys($reflection->getTraits()));
                    }

                    return $valid;
                });

            return $models->values();
        };

        if (config('admin.cache.key', 'admin.cache') && config('admin.manifest', 'api') === 'api') {
            return Cache::remember(
                config('admin.cache.key', 'admin.cache') . '.models', 
                config('admin.cache.ttl', 60), 
                $callback
            );
        }

        return $callback();
    }
}
