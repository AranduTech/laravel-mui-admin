<?php

namespace App\Bella\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet as PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Spreadsheet
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

    static function createSheetFromArray($sheetName, $data, $columns)
    {
        $spreadsheet = new PhpSpreadsheet();

        $sheet = new Worksheet($spreadsheet, $sheetName);
        $sheet->fromArray(
            $columns,
            NULL,
            'A1'
        );

        $count = 2;
        foreach ($data as $sheetData) {
            $sheet->fromArray(
                $sheetData,
                NULL,
                'A' . $count
            );
            $count++;
        }
        // dd($sheet->toArray());

        $spreadsheet->addSheet($sheet);

        static::formatHeadersAndData(
            $spreadsheet->getSheetByName($sheetName),
            [
                [ // Nº Atendimento
                    'width' => 20
                ],
                [ // Nome do Paciente
                    'width' => 35
                ],
                [ // Data do Atendimento
                    'width' => 25,
                    'numberFormat' => NumberFormat::FORMAT_DATE_DDMMYYYY,
                ],
                [ // Paciente Entregou Registro Médico?
                    'width' => 20
                ],
                [ // Duração do Atendimento (minutos)
                    'width' => 20
                ],
                [ // Status do Atendimento
                    'width' => 15
                ],
                [ // Observações
                    'width' => 50
                ],
            ],
            [
                'height' => 50,
            ]
        );
        
        // remove default sheet created
        $spreadsheet->removeSheetByIndex(0);

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

        $i = 0;
        foreach ($sheet->getColumnIterator() as $column) {
            if (isset($formatSchema[$i])) {
                $columnChar = $column->getColumnIndex();
                if (isset($formatSchema[$i]['width'])) {
                    if ($formatSchema[$i]['width'] !== 'auto') {
                        $sheet->getColumnDimension($columnChar)->setWidth(
                            $formatSchema[$i]['width']
                        );
                    }
                }
                // $style = [];

                if (isset($formatSchema[$i]['numberFormat'])) {
                    $sheet
                        ->getStyle($columnChar . '2:' . $columnChar . $limits['row'])
                        ->getNumberFormat()
                        ->setFormatCode($formatSchema[$i]['numberFormat']);
                }
            }

            $i++;
        }

        // foreach ($formatSchema as $format) {
        //     $sheet->getColumnIterator()
        // }
    }
}
