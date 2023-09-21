<?php

namespace Arandu\LaravelMuiAdmin\Traits;

use Arandu\LaravelMuiAdmin\Http\Controllers\RendererController;
use Arandu\LaravelMuiAdmin\Http\Controllers\RepositoryController;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait HasAdminSupport
{
    public function getSyncs()
    {
        if (!isset($this->syncs)) {
            return [];
        }

        return $this->syncs;
    }

    public function getFormFillable()
    {
        if ($form = $this->getFormInstance()) {
            return array_merge($this->fillable, $form->getExtra());
        }

        return $this->fillable;
    }

    public function getSchema()
    {
        return [
            'fillable' => $this->getFormFillable(),
            'fields' => $this->getFieldsDefinition(),
            'tables' => $this->getTablesDefinition(),
            'softDelete' => $this->hasSoftDelete(),
            'web' => array_keys($this->getWebUrls()),
            'relations' => $this->getRelationships(),
        ];
    }

    /**
     * O nome que será usado para identificar a entidade no frontend.
     * Por padrão será o nome da classe em snake_case.
     *
     * @return string
     */
    public function getSchemaName()
    {
        return Str::snake(class_basename($this));
        // strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', class_basename($this)));
    }

    public function getFormClass()
    {
        return 'App\\Admin\\Forms\\' . class_basename($this) . 'Form';
    }

    public function getTableClass()
    {
        return 'App\\Admin\\Tables\\' . class_basename($this) . 'Table';
    }

    /**
     * Obtém uma instância do formulário da entidade.
     *
     * @return null|\App\Contracts\Form
     */
    public function getFormInstance()
    {
        $form = $this->getFormClass();

        if (!class_exists($form)) {
            return null;
        }

        return new $form();
    }

    /**
     * Obtém uma instância da tabela da entidade.
     *
     * @return null|\App\Contracts\Table
     */
    public function getTableInstance()
    {
        $table = $this->getTableClass();

        if (!class_exists($table)) {
            return null;
        }

        return new $table();
    }

    public function hasSoftDelete()
    {
        return in_array(
            \Illuminate\Database\Eloquent\SoftDeletes::class,
            class_uses_recursive(static::class)
        );
    }

    public function getFieldsDefinition(): array
    {
        $definitions = [];
        // dd('App\\Frontend\\Forms\\' . class_basename($this) . 'Form');
        if ($formInstance = $this->getFormInstance()) {
            // iterate through instance methods
            foreach (get_class_methods($formInstance) as $method) {
                if (in_array(
                    $method,
                    [
                        'getExtra',
                        'getRequestFormType',
                        'getRequestAction',
                        'validate',
                        'getInitialFormValues',
                    ]
                )) {
                    continue;
                }
                $definitions[$method] = $formInstance->{$method}();
            }

            return $definitions;
        }

        foreach ($this->getFormFillable() as $fillable) {
            $definitions[] = [
                'name' => $fillable,
            ];
        }

        return [
            'default' => $definitions,
        ];
    }

    public function getTablesDefinition(): array
    {
        $definitions = [];

        // dd('App\\Frontend\\Forms\\' . class_basename($this) . 'Form');
        if ($tableInstance = $this->getTableInstance()) {
            // iterate through instance methods
            foreach (get_class_methods($tableInstance) as $method) {
                // if (in_array($method, ['getExtra', 'getRequestFormType', 'getRequestAction', 'validate'])) {
                //     continue;
                // }
                $definitions[$method] = $tableInstance->{$method}();
            }

            return $definitions;
        }

        foreach ($this->getFillable() as $fillable) {
            $definitions[] = [
                'key' => $fillable,
                'label' => __($fillable),
            ];
        }

        return [
            'default' => $definitions,
        ];
    }

    public function scopeBeginCmsQuery($query, $request)
    {
        return $query;
    }

    public function scopeWhereCurrentUserCan($query, $action)
    {
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        foreach ($this->getFillable() as $fillable) {
            $query->orWhere($fillable, 'like', '%' . $search . '%');
        }

        return $query;
    }

    public function scopeWhereBelongsToTab($query, $tab)
    {
        if ('trashed' == $tab) {
            $query = $query->onlyTrashed();
        }

        return $query;
    }

    public function getWebUrls()
    {
        return [
            'index' => Str::plural($this->getSchemaName()),
            // 'new' => $this->getSchemaName() . '/create',
            // 'edit' => $this->getSchemaName() . '/update',
            // 'item' => $this->getSchemaName() . '/{id}',
        ];
    }

    // 'user.list'
    public function getApiUrls()
    {
        $apiUrls = [
            'list' => Str::plural($this->getSchemaName()),
            'item' => $this->getSchemaName() . '/{id}',
            'create' => [
                'url' => Str::plural($this->getSchemaName()),
                'method' => 'post',
            ],
            'update' => [
                'url' => $this->getSchemaName() . '/{id}',
                'method' => 'post',
            ],
            'delete' => [
                'url' => $this->getSchemaName() . '/{id}',
                'method' => 'delete',
            ],
        ];

        if ($this->hasSoftDelete()) {
            $apiUrls['restore'] = [
                'url' => $this->getSchemaName() . '/{id}/restore',
                'method' => 'post',
            ];
            $apiUrls['forceDelete'] = [
                'url' => $this->getSchemaName() . '/{id}/force',
                'method' => 'delete',
            ];
        }

        return $apiUrls;
    }

    public function mapApiActionToAbility($action)
    {
        $map = [
            'index' => 'read',
            'list' => 'read',
            'store' => 'create',
            'get' => 'read',
            'update' => 'update',
            'delete' => 'delete',
        ];

        if (!isset($map[$action])) {
            return $action;
        }

        return $map[$action];
    }

    public function web()
    {
        $urls = $this->getWebUrls();

        foreach ($urls as $page => $url) {
            Route::get($url, [RendererController::class, 'render'])
                ->name('admin.' . $this->getSchemaName() . '.' . $page);
        }
    }

    public function api()
    {
        $urls = $this->getApiUrls();

        foreach ($urls as $action => $url) {
            $method = 'get';

            if (is_array($url)) {
                $method = $url['method'];
                $url = $url['url'];
            }

            Route::$method($url, [RepositoryController::class, $action])
                ->name('admin.' . $this->getSchemaName() . '.' . $action);
        }
    }

    /**
     * Get eloquent relationships
     *
     * @return array
     */
    public function getRelationships()
    {
        $class = new \ReflectionClass(static::class);

        $allMethods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methods = array_filter(
            $allMethods,
            function (\ReflectionMethod $method) use ($class) {
                return $method->getFileName() === $class->getFileName() // only methods declared in the model
                    && !$method->getParameters() // relationships have no parameters
                    && $method->hasReturnType() // check if the method has a return type
                    && is_subclass_of($method->getReturnType()->getName(), \Illuminate\Database\Eloquent\Relations\Relation::class); // check if the return type is a subclass of Relation
            }
        );

        $relations = [];
        foreach ($methods as $method) {
            try {
                $methodName = $method->getName();
                $returnType = $method->getReturnType()->getName();

                $type = (new \ReflectionClass($returnType))->getShortName();

                /** @var Relation */
                $relation = $this->{$methodName}();
                $relatedModel = $relation->getRelated();

                $model = get_class($relatedModel);

                $relations[Str::snake($methodName)] = [
                    'type' => $type,
                    'model' => (new $model())->getSchemaName(),
                ];
            } catch (\Throwable $th) {
                continue;
            }
        }

        return empty($relations)
            ? null
            : $relations;
    }
}
