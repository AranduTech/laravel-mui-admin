<?php

namespace Arandu\LaravelMuiAdmin\Traits;

trait Exportable
{
    public static function createExportablesSpreadsheet($spreadsheet, $query)
    {
        $entity = new self;

        // Pega a instância ativa da planilha
        $sheet = $spreadsheet->getActiveSheet();

        // Define o título das colunas na planilha
        $columns = $entity->getFillable();

        // Preenche o título das colunas na planilha
        foreach ($columns as $index => $column) {
            $sheet->setCellValueByColumnAndRow($index + 1, 1, $column);
        }

        $query_results = $query->select($columns)->get();

        // Preenche as células abaixo com os dados
        foreach ($query_results as $rowIndex => $item) {
            foreach ($item->toArray() as $columnIndex => $value) {
                $sheet->setCellValueByColumnAndRow($columnIndex + 1, $rowIndex + 2, $value);
            }
        }

        // Define a cor de fundo
        $sheet->getStyle('A1:Z1000') // Intervalo ajustável de acordo à necessidade
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('5952CB'); // Set cell background color

        // Define as células A2 a D201 para sem formatação
        $sheet->getStyle('A2:D201')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_NONE);

        // Formata a fonte da primeira linha em negrito, Arial, tamanho 14
        $sheet->getStyle('A1:Z1') // Intervalo ajustável de acordo à necessidade
            ->getFont()
            ->setBold(true)
            ->setName('Arial')
            ->setSize(14);

            // Centraliza o conteúdo da primeira linha
        $sheet->getStyle('A1:Z1') // Intervalo ajustável de acordo à necessidade
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Ajusta a largura da coluna de acordo com o conteúdo
        for ($i = 'A'; $i !== 'Z'; $i++) {
            $sheet->getColumnDimension($i)->setAutoSize(true);
        }
    }
}