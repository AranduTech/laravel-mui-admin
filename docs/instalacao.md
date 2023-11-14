
## Instalação

 > Este pacote foi pensado para projetos iniciando do zero. Se você já possui um projeto em andamento, pode ser necessário adaptar algumas coisas para que este pacote funcione corretamente.

Pré-requisitos:

    - PHP ^7.4|^8.0
    - Laravel 8.x - Para laravel 9.x, use a branch `laravel-9.x`
    - Node 14.x ou superior

Siga os passos de instalação para [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v5/introduction) e instale o pacote [Laravel UI](https://github.com/laravel/ui), pulando a etapa de geração do scaffold.

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
composer require laravel/ui
```
 > **Nota:** Existem etapas não descritas aqui. Por favor, siga a documentação de cada pacote.

Em seguida, instale este pacote:

```bash
composer require arandu/laravel-mui-admin
```

Publique a configuração e os arquivos:

```bash
php artisan vendor:publish --provider="Arandu\\LaravelMuiAdmin\\AdminServiceProvider"
php artisan ui mui --auth
```

Instale as dependências do Node e compile o frontend:

```bash
npm install && npm run dev
```

## Configuração

Adicione as seguintes linhas de código ao arquivo de rota `web.php`:

```php
use Arandu\LaravelMuiAdmin\Facades\Admin;
use Illuminate\Support\Facades\Auth;

Auth::routes();
Admin::web();
```

Adicione as seguintes linhas de código ao arquivo de rota `api.php`:

```php
use Arandu\LaravelMuiAdmin\Facades\Admin;

Admin::api();
```

No arquivo `app/Providers/RouteServiceProvider.php`, mude a constante `HOME` para `'/'`. Exemplo:

```php
    public const HOME = '/';
```

Verifique se o trait `HasRoles` foi adicionado ao modelo `User.php`.
Depois adicione os traits `HasAdminSupport` e `RendersReactView`. Exemplo:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Arandu\LaravelMuiAdmin\Traits\HasAdminSupport;
use Arandu\LaravelMuiAdmin\Traits\RendersReactView;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasAdminSupport;
    use RendersReactView;
    use HasRoles;

    // ...
}
```

Configure a autenticação para a API. Recomendamos o [Sanctum](https://laravel.com/docs/8.x/sanctum) (necessária instalação). Exemplo:

```php
# config/auth.php
    // ...
    'guards' => [
        // ...
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'users',
        ],
    ],
```
