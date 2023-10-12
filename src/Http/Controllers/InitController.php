<?php

namespace Arandu\LaravelMuiAdmin\Http\Controllers;

use Arandu\LaravelMuiAdmin\Services\AdminService;
use Arandu\LaravelMuiAdmin\Services\JsService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Traits\Macroable;

class InitController extends BaseController
{

    use Macroable;

    public function init(AdminService $adminService, JsService $jsService)
    {
        $user = auth()->user();
        $jsService->set('user', $user);

        if (static::hasMacro('onInit')) {
            static::onInit($jsService);
        }

        return response()->json([
            'routes' => $adminService->getRoutes(),
            'models' => $user
                ? $adminService->getModelSchema()
                : null,
            'data' => $jsService->all(),
        ]);
    }
}
