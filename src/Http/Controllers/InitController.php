<?php

namespace Arandu\LaravelMuiAdmin\Http\Controllers;

use Arandu\LaravelMuiAdmin\Services\AdminService;
use Arandu\LaravelMuiAdmin\Services\JsService;
use Illuminate\Routing\Controller as BaseController;

class InitController extends BaseController
{

    private $adminService;
    private $jsService;

    public function __construct(AdminService $adminService, JsService $jsService)
    {
        $jsService->set('user', auth()->user());

        $this->adminService = $adminService;
        $this->jsService = $jsService;
    }

    public function init()
    {
        return response()->json([
            'routes' => $this->adminService->getRoutes(),
            'models' => $this->adminService->getModelSchema(),
            'data' => $this->jsService->all(),
        ]);
    }
}
