<?php


namespace Arandu\LaravelMuiAdmin\Contracts;

use Illuminate\Support\Collection;

abstract class Dashboard
{

    /**
     * A unique identifier for this dashboard. Will be used to generate the route name. 
     * 
     * @var string
     */
    protected $id;

    /**
     * The title of this dashboard.
     *
     * @var string
     */
    protected $title;

    /**
     * The model class for this dashboard.
     *
     * @var string
     */
    protected $model;

    /**
     * The widgets for this dashboard.
     *
     * @var Collection<Widget>
     */
    protected $widgets;

    public function __construct()
    {
        $this->widgets = new Collection();
    }


    // /**
    //  * Add widgets to this dashboard.
    //  * 
    //  * @param Widget|array<Widget>|Collection $widgets 
    //  * @return $this 
    //  */
    // public function withWidgets($widgets)
    // {
    //     if (is_array($widgets) || $widgets instanceof Collection) {
    //         $this->widgets = $this->widgets->merge($widgets);
    //     } else {
    //         $this->widgets->push($widgets);
    //     }

    //     return $this;
    // }


    /**
     * Register widgets for this dashboard.
     * 
     * @return array<Widget> 
     */
    abstract function widgets(): array;
    
}
