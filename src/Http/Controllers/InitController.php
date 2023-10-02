<?php

namespace Arandu\LaravelMuiAdmin\Http\Controllers;

use Arandu\LaravelMuiAdmin\Services\AdminService;
use Arandu\LaravelMuiAdmin\Services\JsService;
use Illuminate\Routing\Controller as BaseController;

class InitController extends BaseController
{

    public function init(AdminService $adminService, JsService $jsService)
    {
        $jsService->set('user', auth()->user());

        return response()->json([
            'routes' => $adminService->getRoutes(),
            'models' => $adminService->getModelSchema(),
            'data' => $jsService->all(),
        ]);
    }
}
