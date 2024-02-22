<?php

namespace Arandu\LaravelMuiAdmin\Traits;

trait Exportable
{

    public static function getExportsHeadings(
        $removeColumns = [],
        $extraColumns = [],
        $translatedColumns = null
    ): array {

        /** @var \Illuminate\Database\Eloquent\Model */
        $instance = new static;
        
        $columns = $instance->getFillable();

        if (!in_array($instance->getKeyName(), $columns)) {
            $columns = [
                $instance->getKeyName(),
                ...array_diff($columns, [$instance->getKeyName()])
            ];
        }

        if ($instance->usesTimestamps()) {
            $columns = [
                ...$columns,
                $instance->getCreatedAtColumn(),
                $instance->getUpdatedAtColumn(),
            ];
        }

        $columns = array_merge(
            $columns,
            $extraColumns
        );

        $columns = array_diff(
            $columns, 
            $removeColumns
        );

        if ($translatedColumns) {
            $columns = array_map(function ($column) use ($translatedColumns) {
                return $translatedColumns[$column] ?? $column;
            }, $columns);
        }

        return $columns;
    }

    public function getExportsData()
    {
        $columns = $this->getExportsHeadings();

        $data = [];

        foreach ($columns as $column) {
            $data[] = $this->{$column};
        }

        return $data;
    }
}