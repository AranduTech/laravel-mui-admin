<?php

namespace Arandu\LaravelMuiAdmin\Http\Controllers;

use Arandu\LaravelMuiAdmin\Facades\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

use PhpOffice\PhpSpreadsheet\Spreadsheet as PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        
        $filters = $request->filters ?? [];

        $widgets = $dashboard->widgets();

        $spreadsheet = new PhpSpreadsheet();

        foreach ($widgets as $i => $widget) {
            $item = $dashboard->execute($request, $widget->uri, $filters)->first();

            $attributes = $item->attributes;

            if (empty($attributes)) {
                break;
            }

            $values = [];
            foreach (array_keys($attributes) as $key) {
                $values[] = $attributes[$key];
            }

            $widgetJson = $widget->jsonSerialize();
            
            $sheet = $i === 0
                ? $spreadsheet->getActiveSheet()
                : new Worksheet($spreadsheet);
            $sheet->setTitle($widgetJson['title']);

            $header = array_merge(
                [],
                // $filters,
                array_keys($attributes),
            );

            $sheet->fromArray(
                $header,
                NULL,
                'A1'
            );

            $count = 2;
            foreach ($values as $value) {
                $sheet->fromArray(
                    $value,
                    NULL,
                    "A{$count}"
                );
                $count++;
            }

            $spreadsheet->addSheet($sheet);
            $spreadsheet->setActiveSheetIndex($i + 1);
        }

        $dashboardJson = $dashboard->jsonSerialize();

        $filename = Str::plural($dashboardJson['title']);

        // Prepare headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);

        // Save to php://output
        $writer->save('php://output');

        return response()->json(['message' => 'OK'], 200);
    }
}