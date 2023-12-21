<?php


namespace Arandu\LaravelMuiAdmin\Contracts;

use Illuminate\Support\Collection;

abstract class Dashboard implements \JsonSerializable
{

    /**
     * A unique identifier for this dashboard. Will be used to generate the route name. 
     * 
     * @var string
     */
    protected $uri;

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
     * Register widgets for this dashboard.
     * 
     * @return Widget[]
     */
    abstract function widgets(): array;


    public function execute($widgetId, $filters = [])
    {
        $widgets = collect($this->widgets());

        /** @var Widget */
        $widget = $widgets->firstWhere('id', $widgetId);

        if (!$widget) {
            abort(404);
        }

        $query = $this->model::query()
            ->whereMatchesFilter($filters);

        return $widget->execute($query);

    }


    public function jsonSerialize(): mixed
    {
        return [
            'uri' => $this->uri,
            'title' => $this->title,
            'widgets' => $this->widgets(),
        ];
    }
    
}
