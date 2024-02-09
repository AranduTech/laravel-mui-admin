<?php

namespace Arandu\LaravelMuiAdmin\Http\Controllers;

use Arandu\LaravelMuiAdmin\Facades\Dashboard;
use Arandu\LaravelMuiAdmin\Facades\Spreadsheet;

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

        return response()->json($dashboard->execute($request, $widget));
    }

    public function export(Request $request, $dashboard)
    {
        // dd($request->all());

        $dashboard = Dashboard::find($dashboard);

        if (!$dashboard) {
            abort(404);
        }
        
        $filters = $request->filters ?? [];

        $widgets = $dashboard->widgets();

        $spreadsheet = new PhpSpreadsheet();

        foreach ($widgets as $i => $widget) {
            $item = $dashboard->execute($request, $widget->uri, $filters)->first();

            $itemCollection = collect($item);

            $attributes = $itemCollection->toArray();

            if (!empty($attributes)) {
                $attributeKeys = array_keys($attributes);

                $header = array_merge(
                    $filters,
                    $attributeKeys,
                );

                $values = [];

                if (isset($filters['users']) && !empty($filters['users'])) {
                    foreach ($filters['users'] as $user) {
                        $_val = [];
                        $_val['user'] = $user['value'];

                        foreach ($attributes as $key => $attribute) {
                            if (empty($attribute)) {
                                $attribute = 'N/A';
                            }

                            if (is_array($attribute) || is_object($attribute)) {
                                $_val[$key] = json_encode($attribute);
                            } else {
                                $_val[$key] = $attribute;
                            }
                        }

                        $values[] = $_val;
                    }
                } else {
                    $_val= [];
                    
                    foreach ($attributes as $key => $attribute) {
                        if (empty($attribute)) {
                            $attribute = 'N/A';
                        }

                        if (is_array($attribute) || is_object($attribute)) {
                            $_val[$key] = json_encode($attribute);
                        } else {
                            $_val[$key] = $attribute;
                        }
                    }

                    $values[] = $_val;
                }
                // dd($values);

                $widgetJson = $widget->jsonSerialize();

                $sheetName = $widgetJson['title'];

                $spreadsheet = Spreadsheet::createSheetFromArray(
                    $sheetName,
                    $values,
                    $header,
                    $spreadsheet,
                    $i
                );
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

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