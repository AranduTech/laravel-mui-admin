<?php

namespace Arandu\LaravelMuiAdmin\Http\Controllers;

use Arandu\LaravelMuiAdmin\Services\AdminService;
use Arandu\LaravelMuiAdmin\Services\JsService;
use Illuminate\Routing\Controller;

class RendererController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function render(AdminService $admin, JsService $js)
    {
        $user = auth()->user();

        if (!$user) {
            return view('guest')->with(['admin' => $admin, 'js' => $js]);
        }

        $js->set('user', $user);
        $js->set('model-schema', $admin->getModelSchema());

        return view('admin')->with([
            'admin' => $admin, 
            'js' => $js
        ]);
    }
}
