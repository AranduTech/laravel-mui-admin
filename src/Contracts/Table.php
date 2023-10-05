<?php

namespace Arandu\LaravelMuiAdmin\Contracts;

abstract class Table 
{
    protected $filterForm;

    private $filterFormInstance;

    abstract public function default();

    public function getFilterFormClass()
    {
        return $this->filterForm;
    }

    public function filter()
    {
        if (!isset($this->filterFormInstance)) {
            $this->filterFormInstance = new $this->filterForm();
        }
        return $this->filterFormInstance;
    }
}