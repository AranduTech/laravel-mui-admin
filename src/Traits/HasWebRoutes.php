<?php

namespace Arandu\LaravelMuiAdmin\Traits;

use Arandu\LaravelMuiAdmin\Http\Controllers\RendererController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use InvalidArgumentException;

trait HasWebRoutes
{
    public function getDefaultWebUrls()
    {
        return [
            'index' => Str::plural($this->getSchemaName()),
            // 'new' => $this->getSchemaName() . '/create',
            // 'edit' => $this->getSchemaName() . '/update',
            // 'item' => $this->getSchemaName() . '/{id}',
        ];
    }

    /**
     * 
     * @return string[] 
     * @throws InvalidArgumentException 
     */
    public function getWebUrls()
    {
        return array_merge(
            $this->getDefaultWebUrls(),
            [
                //
            ]
        );
    }

    public function web()
    {
        $urls = $this->getWebUrls();

        foreach ($urls as $page => $url) {
            Route::get($url, [RendererController::class, 'render'])
                ->name('admin.' . $this->getSchemaName() . '.' . $page);
        }
    }

}