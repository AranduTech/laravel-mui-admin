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

        foreach ($widgets as $widget) {
            $item = $dashboard->execute($request, $widget->uri, $filters)->first();

            $data = $item->attributes;

            if (empty($data)) {
                break;
            }

            $header = array_merge(
                [],
                // $filters,
                array_keys($data),
            );

            $widgetJson = $widget->jsonSerialize();
            
            $sheet = new Worksheet($spreadsheet, $widgetJson['title']);
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

            $spreadsheet->addSheet($sheet);
        }
        
        // remove default sheet created
        $spreadsheet->removeSheetByIndex(0);

        $dashboardJson = $dashboard->jsonSerialize();

        $filename = Str::plural($dashboardJson['title']);

        // Prepare headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);

        // Save to php://output
        $writer->save('php://output');

        return response()->json(['message' => 'OK'], 200);
    }
}