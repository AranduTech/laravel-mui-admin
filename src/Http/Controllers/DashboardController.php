<?php

namespace Arandu\LaravelMuiAdmin\Http\Controllers;

use Arandu\LaravelMuiAdmin\Facades\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function dashboard($dashboard)
    {
        $dashboard = Dashboard::find($dashboard);

        if (!$dashboard) {
            abort(404);
        }

        return response()->json($dashboard);

    }

    public function widget(Request $request, $dashboard, $widget)
    {
        $dashboard = Dashboard::find($dashboard);

        if (!$dashboard) {
            abort(404);
        }

        $filters = [];

        if ($request->has('filters')) {
            $filters = json_decode($request->get('filters'), true);
        }

        return response()->json($dashboard->execute($widget, $filters));
    }

    public function export(Request $request, $dashboard, $widget)
    {
        
    }
}