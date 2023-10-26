Adicionar as traits na model, exemplo:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Arandu\LaravelMuiAdmin\Traits\Importable;
use Arandu\LaravelMuiAdmin\Traits\Exportable;

class User extends Authenticatable
{
    use Importable;
    use Exportable;
    // ...
}
```

Adicionar o método personalizado para cada trait na model, exemplo:

* `Importable`

```php
    public static function createElementsFromSpreadsheet($spreadsheet)
    {
        DB::beginTransaction();

        $worksheet = $spreadsheet->getActiveSheet(); // Obtém a primeira planilha do arquivo
        $highestRow = $worksheet->getHighestRow();

        for ($row = 2; $row <= $highestRow; $row++) {
            $cells = $worksheet->rangeToArray("A{$row}:Z{$row}");
            if (empty(array_filter($cells[0]))) { // Se a linha estiver vazia, ignora a mesma.
                continue;
            }
            
            $data = [
                'name' => $worksheet->getCell('A'.$row)->getValue(),
                'email' => $worksheet->getCell('B'.$row)->getValue(),
                'password' => $worksheet->getCell('C'.$row)->getValue(), // $password = strtoupper(substr($name, 0, 2)) . $day . $year;
                'remember_token' => $worksheet->getCell('D'.$row)->getValue(),
            ];
            
            self::create($data); // salva os dados definidos no array
        }
        
        DB::commit();
    }
```

* `Exportable`
```php
    public static function createExportablesSpreadsheet($spreadsheet)
    {
        $sheet = $spreadsheet->getActiveSheet(); // Pega a instância ativa da planilha

        // Define o título das colunas na planilha
        $columns = ['Nome', 'E-mail', 'Senha', 'Telefone'];

        // Write columns to the first row
        foreach ($columns as $index => $column) {
            $sheet->setCellValueByColumnAndRow($index + 1, 1, $column);
        }

        // Define a cor de fundo para amarelo
        $sheet->getStyle('A1:Z1000') // Intervalo ajustável de acordo à necessidade
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FFFF00'); // Define o background color da célula para amarelo

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
        for ($i = 'A'; $i !== 'E'; $i++) {
            $sheet->getColumnDimension($i)->setAutoSize(true);
        }
    }
```