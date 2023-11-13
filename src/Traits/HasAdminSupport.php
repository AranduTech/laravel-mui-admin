<?php

namespace Arandu\LaravelMuiAdmin\Traits;

use Arandu\LaravelMuiAdmin\Http\Controllers\RendererController;
use Arandu\LaravelMuiAdmin\Http\Controllers\RepositoryController;

use Illuminate\Database\Eloquent\Relations\Relation;

use Illuminate\Support\Str;

trait HasAdminSupport
{

    use DeactivatesAppends;
    use Formable;
    use HasApiRoutes;
    use HasCmsQueryScopes;
    use HasWebRoutes;
    use SyncsBelongsToManyRelations;
    use Tableable;

    public function getSchema()
    {
        return [
            'fillable' => $this->getFormFillable(),
            'fields' => $this->getFieldsDefinition(),
            'tables' => $this->getTablesDefinition(),
            'softDelete' => $this->hasSoftDelete(),
            'importable' => $this->hasImportable(),
            'exportable' => $this->hasExportable(),
            'web' => array_keys($this->getWebUrls()),
            'relations' => $this->getRelationships(),
            'class' => static::class,
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


    public function hasSoftDelete()
    {
        return in_array(
            \Illuminate\Database\Eloquent\SoftDeletes::class,
            class_uses_recursive(static::class)
        );
    }

    public function hasImportable()
    {
        return in_array(
            \Arandu\LaravelMuiAdmin\Traits\Importable::class,
            class_uses_recursive(static::class)
        );
    }

    public function hasExportable()
    {
        return in_array(
            \Arandu\LaravelMuiAdmin\Traits\Exportable::class,
            class_uses_recursive(static::class)
        );
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
            function (\ReflectionMethod $method) {
                return // $method->getFileName() === $class->getFileName() && // only methods declared in the model
                    !$method->getParameters() // relationships have no parameters
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
