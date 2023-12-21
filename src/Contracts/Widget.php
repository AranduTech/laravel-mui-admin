<?php


namespace Arandu\LaravelMuiAdmin\Contracts;

use Arandu\LaravelMuiAdmin\Contracts\Dimension;
use Arandu\LaravelMuiAdmin\Contracts\Metric;
use Illuminate\Database\Eloquent\Builder;
use JsonSerializable;

class Widget implements JsonSerializable
{

    /**
     * The unique identifier of the widget.
     * 
     * @var string
     */
    public $uri;

    /**
     * The layout of the widget.
     *
     * @var array
     */
    private $layout;

    /**
     * @var Dimension[]
     */
    private $xAxis = [];

    /**
     * @var Dimension[]
     */
    private $groups = [];

    /**
     * @var Metric[]
     */
    private $values = [];

    /**
     * @var callable[]
     */
    private $scopes = [];

    /**
     * @var callable
     */
    private $customData;
    

    public function __construct(
        public $title,
    ) {
        
        $this->uri = \Illuminate\Support\Str::slug($title);
    }

    /**
     * Create a new widget instance.
     * 
     * @param mixed $title 
     * 
     * @return static 
     */
    public static function create($title)
    {
        return new static($title);
    }

    /**
     * Attach one or more dimensions to the widget.
     * 
     * @param Dimension[]|Dimension $dimension 
     *  
     * @return $this 
     */
    public function attach($dimension, &$array)
    {
        if (is_array($dimension) || $dimension instanceof \Illuminate\Support\Collection) {
            if ($dimension instanceof \Illuminate\Support\Collection) {
                $dimension = $dimension->all();
            }
            $array = array_merge($array, $dimension);
        }
        $array[] = $dimension;

        return $this;
    }

    /**
     * Set the custom uri key for this widget.
     * 
     * @param string $uri - The custom uri key for this widget.
     * @return $this 
     */
    public function withUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Set the layout of the widget.
     * 
     * @param array $layout - The layout of the widget.
     * @return $this 
     */
    public function withLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Set the dimension or dimensions for the x-axis of the widget.
     * 
     * @param string $title - The title of the widget.
     * @return $this 
     */
    public function withXAxis($xAxis)
    {
        return $this->attach($xAxis, $this->xAxis);
    }

    /**
     * Set the dimension or dimensions for the groups of the widget.
     * 
     * @param string $title - The title of the widget.
     * @return $this 
     */
    public function withGroups($groups)
    {
        return $this->attach($groups, $this->groups);
    }

    /**
     * Set the metric or metrics for the values of the widget.
     * 
     * @param string $title - The title of the widget.
     * @return $this 
     */
    public function withValues($values)
    {
        return $this->attach($values, $this->values);
    }

    /**
     * Set the scope or scopes of the widget.
     * 
     * @param string $title - The title of the widget.
     * @return $this 
     */
    public function withScopes($scopes)
    {
        return $this->attach($scopes, $this->scopes);
    }

    public function withCustomData(callable $callback)
    {
        $this->customData = $callback;

        return $this;
    }


    public function execute(Builder $query)
    {
        if ($this->customData) {
            return call_user_func($this->customData, $query);
        }

        foreach ($this->scopes as $scope) {
            $query->where($scope);
        }

        $dimensions = collect(array_merge($this->xAxis, $this->groups, $this->values));

        return $dimensions->reduce(function ($query, Dimension $dimension) {
                return $dimension->apply($query);
            }, $query)
            ->get(
                $dimensions->map(function (Dimension $dimension) {
                    return $dimension->select();
                })->all()
            );

    }

    public function jsonSerialize(): mixed { 
        return [
            'uri' => $this->uri,
            'title' => $this->title,
            'layout' => $this->layout,
            'groups' => $this->groups,
            'xAxis' => $this->xAxis,
            'values' => $this->values,
        ];
    }
    
}
