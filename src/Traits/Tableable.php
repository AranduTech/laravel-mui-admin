<?php

namespace Arandu\LaravelMuiAdmin\Traits;

/**
 * Allows the class to have associated `Arandu\LaravelMuiAdmin\Contracts\Table` instance.
 */
trait Tableable
{

    public function getTableClass()
    {
        $class = 'App\\Admin\\Tables\\' . class_basename($this) . 'Table';

        if (isset($this->tableClass)) {
            $class = $this->tableClass;
        }

        if (class_exists($class)) {
            return $class;
        }

        return null;
    }

    /**
     * Obtém uma instância da tabela da entidade.
     *
     * @return null|\Arandu\LaravelMuiAdmin\Contracts\Table
     */
    public function getTableInstance()
    {
        $table = $this->getTableClass();

        if (!$table) {
            return null;
        }

        return new $table();
    }

    public function getTablesDefinition(): array
    {
        $definitions = [];

        if ($tableInstance = $this->getTableInstance()) {
            // iterate through instance methods
            foreach (get_class_methods($tableInstance) as $method) {
                $reflection = new \ReflectionMethod($tableInstance, $method);
                if ($reflection->getDeclaringClass()->getName() !== $this->getTableClass()) {
                    continue;
                }
                $definitions[$method] = [
                    'columns' => $tableInstance->{$method}()
                ];
                if (
                    $tableInstance->getFormClass() 
                    && method_exists($tableInstance->getFormInstance(), $method)
                ) {
                    $definitions[$method]['filter'] = $tableInstance->getFormInstance()->{$method}();
                }
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
            'default' => [
                'columns' => $definitions,
            ],
        ];
    }
}