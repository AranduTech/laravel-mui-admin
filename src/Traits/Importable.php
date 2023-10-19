<?php

namespace Arandu\LaravelMuiAdmin\Traits;

use Illuminate\Support\Facades\DB;

trait Importable
{
    public static function createElementsFromSpreadsheet($spreadsheet)
    {
        $entity = new self;
        $Model = $entity->getModel();

        DB::beginTransaction();

        $worksheet = $spreadsheet->getActiveSheet(); // Obtém a primeira planilha do arquivo
        $highestRow = $worksheet->getHighestRow();

        $columnNames = [];
        for ($col = 'A'; $col !== 'Z'; $col++) {
            $columnNames[] = $worksheet->getCell($col . '1')->getFormattedValue();
        }

        $data = [];
        for ($row = 2; $row <= $highestRow; $row++) {
            $cells = $worksheet->rangeToArray("A{$row}:Z{$row}");
                    if (empty(array_filter($cells[0]))) { // Se a linha estiver vazia, ignora a mesma.
                        continue;
                    }

            $rowData = [];
            foreach ($columnNames as $index => $columnName) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
                $cellValue = $worksheet->getCell($columnLetter . $row)->getValue();
                $rowData[$columnName] = $cellValue;
            }
            $data[] = $rowData;
        }

        $data = array_filter($data, function($value) {
            return !is_null($value);
        });

        foreach ($data as $entityData) {
            $Model::create($entityData);
        }

        DB::commit();

    }

    public function getModel()
    {
        return get_class($this);
    }
}
