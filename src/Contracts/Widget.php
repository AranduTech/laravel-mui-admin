<?php


namespace Arandu\LaravelMuiAdmin\Contracts;

use JsonSerializable;

class Widget implements JsonSerializable
{
    private $grid = ['xs' => 12, 'lg' => 4];
    private $xAxis = [];
    private $yAxis = [];
    private $series = [];
    private $dateset = [];

    public function __construct(
        public $title,
        public $type = 'line',
    ) {
        
    }

    public function jsonSerialize(): mixed { 
        return [
            'title' => $this->title,
            'type' => $this->type,
            'grid' => $this->grid,
            'xAxis' => $this->xAxis,
            'yAxis' => $this->yAxis,
            'series' => $this->series,
            'dateset' => $this->dateset,
        ];
    }

    public static function create($title)
    {
        return new static($title);
    }

    public function withGrid($grid)
    {
        $this->grid = $grid;

        return $this;
    }

    public function withXAxis($xAxis)
    {
        $this->xAxis = $xAxis;

        return $this;
    }

    
}
