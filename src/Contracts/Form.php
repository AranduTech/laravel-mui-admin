<?php

namespace Arandu\LaravelMuiAdmin\Contracts;

use Illuminate\Http\Request;

abstract class Form
{
    protected $extra = [];

    final public function getExtra()
    {
        return $this->extra;
    }

    /**
     * Obtém os campos padrão do formulário.
     *
     * @return array
     */
    abstract public function default();

    public function getInitialFormValues($schema)
    {
        if (!method_exists($this, $schema)) {
            return [];
        }

        $form = $this->{$schema}();

        $values = [];
        foreach ($form as $field) {
            $values[$field['name']] = $field['initialValue'] ?? '';
        }

        return $values;
    }

    public function getRequestFormType()
    {
        $type = 'default';
        if (request()->_type) {
            $type = request()->_type;
        }

        return $type;
    }

    public function getRequestAction()
    {
        [$name, $action] = explode('.', request()->route()->getName());

        return $action;
    }

    public function validate(Request $request)
    {
    }
}