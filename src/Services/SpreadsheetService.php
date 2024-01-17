<?php

namespace Arandu\LaravelMuiAdmin\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet as PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SpreadsheetService
{
    static function createWorkbookFromArray($data)
    {
        $spreadsheet = new PhpSpreadsheet();

        foreach ($data as $sheetName => $sheetData) {
            $sheet = new Worksheet($spreadsheet, $sheetName);
            $sheet->fromArray(
                $sheetData,
                NULL,
                'A1'
            );
            $spreadsheet->addSheet($sheet);
        }

        // remove default sheet created
        $spreadsheet->removeSheetByIndex(0);

        return $spreadsheet;
    }

    public function createSheetFromArray(
        $sheetName, 
        array $data, 
        array $header, 
        $spreadsheet = new PhpSpreadsheet(), 
        $loop = 0
    ) {
        // var_dump($data);

        $sheet = $loop === 0
            ? $spreadsheet->getActiveSheet()
            : new Worksheet($spreadsheet);
        $sheet->setTitle($sheetName);

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
        // dd($sheet->toArray());

        if ($loop > 0) {
            $spreadsheet->addSheet($sheet);
        }

        $headerConfig = [];
        foreach ($header as $i => $h) {
            $headerConfig[$i] = [
                'width' => 50,
            ];
        }

        self::formatHeadersAndData(
            $spreadsheet->getSheetByName($sheetName),
            $headerConfig,
            [
                'height' => 50,
            ]
        );

        return $spreadsheet;
    }

    public static function formatHeadersAndData(
        Worksheet $sheet, 
        $formatSchema = [], 
        $args = [
            'height' => 100,
        ]
    ) {
        $limits = $sheet->getHighestRowAndColumn();

        $sheet
            ->getStyle('A1:' . $limits['column'] . '1')
            ->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => [
                        'argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE
                    ]
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'inside' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'argb' => 'FFF28482',
                    ]
                ],
            ]);


        $sheet
            ->getStyle('A2:' . $limits['column'] . $limits['row'])
            ->applyFromArray([
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'inside' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);

        $sheet->getRowDimension(1)->setRowHeight($args['height'], 'pt');

        $columns = $sheet->getColumnIterator();

        foreach ($columns as $c => $column) {
            if (isset($formatSchema[$c])) {
                $columnChar = $column->getColumnIndex();
                if (isset($formatSchema[$c]['width'])) {
                    if ($formatSchema[$c]['width'] !== 'auto') {
                        $sheet->getColumnDimension($columnChar)->setWidth(
                            $formatSchema[$c]['width']
                        );
                    }
                }

                if (isset($formatSchema[$c]['numberFormat'])) {
                    $sheet
                        ->getStyle($columnChar . '2:' . $columnChar . $limits['row'])
                        ->getNumberFormat()
                        ->setFormatCode($formatSchema[$c]['numberFormat']);
                }
            }
        }
    }
}
