<?php

namespace Arandu\LaravelMuiAdmin\Http\Controllers;

use App\Bella\Services\Spreadsheet;
use Arandu\LaravelMuiAdmin\Facades\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Support\Str;

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

        return response()->json($dashboard->execute($request, $widget, $filters));
    }

    public function export(Request $request, $dashboard)
    {
        $dashboard = Dashboard::find($dashboard);

        if (!$dashboard) {
            abort(404);
        }
        
        $filters = $request->filters;

        $widgets = $dashboard->widgets();

        // $tabs = [];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        foreach ($widgets as $widget) {
            $item = $dashboard->execute($request, $widget->uri, $filters)->first();

            $data = $item->attributes->toArray();

            $header = array_merge(
                $item->fillable->toArray(),
                $item->attributes->toArray()
            );
            // $tabs[] = $item->pluck('data')->toArray();
            
            $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet($spreadsheet, $widget->title);
            $sheet->fromArray(
                $header,
                NULL,
                'A1'
            );

            $count = 2;
            foreach ($data as $sheetData) {
                $sheet->fromArray(
                    $sheetData,
                    NULL,
                    "A{$count}"
                );
                $count++;
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $filename = Str::plural($dashboard);

        // Prepare headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $filename .'.xlsx"');

        // Save to php://output
        $writer->save('php://output');

        return response()->json(['message' => 'OK'], 200);
    }
}