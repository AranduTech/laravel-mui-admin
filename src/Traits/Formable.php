<?php

namespace Arandu\LaravelMuiAdmin\Traits;

/**
 * Allows the class to have associated `Arandu\LaravelMuiAdmin\Contracts\Form` instance.
 */
trait Formable
{
    /**
     * Returns an instance of the associated `Arandu\LaravelMuiAdmin\Contracts\Form` class.
     *
     * @return null|\App\Contracts\Form
     */
    public function getFormInstance()
    {
        $form = $this->getFormClass();

        if (!$form) {
            return null;
        }

        return new $form();
    }

    public function getFormFillable()
    {
        if ($form = $this->getFormInstance()) {
            return array_merge($this->fillable, $form->getExtra());
        }

        return $this->fillable;
    }

    public function getFormClass()
    {
        $class = 'App\\Admin\\Forms\\' . class_basename($this) . 'Form';

        if (isset($this->formClass)) {
            $class = $this->formClass;
        }

        if (class_exists($class)) {
            return $class;
        }

        return null;
    }


    public function getFieldsDefinition(): array
    {
        $definitions = [];
        
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
}