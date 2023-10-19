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

    }
```

* `Exportable`
```php
    public static function createExportablesSpreadsheet($spreadsheet)
    {

    }
```