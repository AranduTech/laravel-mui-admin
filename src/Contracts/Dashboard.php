<?php


namespace Arandu\LaravelMuiAdmin\Contracts;

abstract class Dashboard implements \JsonSerializable
{

    /**
     * A unique identifier for this dashboard. Will be used to generate the route name. 
     * 
     * @var string
     */
    public $uri;

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


    public function execute($request, $uri)
    {
        $widgets = collect($this->widgets());

        /** @var Widget */
        $widget = $widgets->firstWhere('uri', $uri);

        if (!$widget) {
            abort(404);
        }

        $query = $this->model::query()
            ->whereCurrentUserCan('read');

        if ($request->has('tab')) {
            $query = $query->whereBelongsToTab($request->tab);
        }

        if ($request->has('q') && !empty($request->q)) {
            $query = $query->search($request->q);
        }

        if ($request->has('filters')) {
            $query = $query->whereMatchesFilter(
                json_decode($request->filters, true)
            );
        }

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
