# Laravel MUI Admin

Um painel administrativo altamente personalizável para Laravel 8.x, construído com [Material-UI](https://material-ui.com/) e [React](https://reactjs.org/), inspirado no Wordpress.

[English docs](README_en.md)

## Instalação

 > **Nota:** Este pacote ainda está em desenvolvimento e não está pronto para uso em produção.

 > **Nota:** Este é um projeto guiado por documentação. O código ainda não está pronto.

 > Se você está instalando este pacote em um projeto existente, certifique-se de ter um backup de seus arquivos.

Pré-requisitos:

    - PHP 7.4
    - Laravel 8.x
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
Depois adicione o trait `HasAdminSupport`. Exemplo:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Arandu\LaravelMuiAdmin\Traits\HasAdminSupport;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasAdminSupport;
    use HasRoles;

    // ...
}
```

Configure a autenticação para a API. Recomendamos o [Sanctum](https://laravel.com/docs/8.x/sanctum). Exemplo:

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

# Uso Básico

 - Criar os papéis iniciais
 - Criar um novo usuário administrador
 - Renderizando o painel administrativo
 - Preparando um modelo para o painel administrativo
    - Adicionando o trait `HasAdminSupport`
    - Os modelos eloquent do frontend
    - Personalização
        - Personalize as colunas da página do modelo
        - Adicionando campos personalizados
        - Adicionando abas personalizadas
        - Adicionando pesquisa personalizada

## Criar os papéis iniciais

Para criar os papéis iniciais, adicione o seeder `RolesAndPermissionsSeeder` ao arquivo `DatabaseSeeder`. Exemplo:

```php
    $this->call([
        // ...
        RolesAndPermissionsSeeder::class,
    ]);
```
Em seguida, execute as classes de seeder com o seguinte comando:

```bash
php artisan db:seed
```

Alternativamente, você pode executar o seguinte comando para semear apenas as funções e permissões:

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

## Criar um novo usuário administrador

Para criar um novo usuário administrador, execute o seguinte comando:

```bash
php artisan admin:credentials
```

Este comando perguntará por um nome de usuário, e-mail e senha, e criará um novo usuário com o papel de `admin`.

## Renderizando o painel administrativo

Se você seguiu as etapas de instalação e todos os ativos foram construídos, você deve ser capaz de acessar o administrador após o login. Use as credenciais que você criou na etapa anterior.

No entanto, se você precisar renderizar manualmente o painel administrativo, consulte a documentação do pacote npm `@arandu/laravel-mui-admin`.

## Preparando um modelo para o painel administrativo

Para preparar um modelo para o painel administrativo, você precisa adicionar o trait `HasAdminSupport` a ele. Esse trait adicionará os métodos necessários ao modelo para fazê-lo funcionar com o painel administrativo.

Além disso, você deve ter a propriedade `$fillable` configurada no modelo, para que o painel administrativo saiba quais campos estão disponíveis para o modelo.

### Adicionando o trait `HasAdminSupport`

```php
use Arandu\LaravelMuiAdmin\Traits\HasAdminSupport;

class Post extends Model
{
    use HasAdminSupport;

    protected $fillable = [
        'title',
        'content',
    ];
}
```

Após adicionar o trait, deve haver uma rota correspondente ao nome do modelo em plural e snake case. Por exemplo, se o modelo se chama `Post`, a rota deve ser `/posts`. Se o modelo se chama `BlogPost`, a rota deve ser `/blog_posts`. Adicione um link no menu lateral alterando o arquivo `resources/js/src/views/Layouts/Admin.jsx`:

```jsx
import { NavLink } from 'react-router-dom';
import { route } from '@arandu/laravel-mui-admin';

const navMenuItems = [
    // ...
    {
        key: 3,
        text: 'Posts',
        icon: 'posts',
        ListItemButtonProps: {
            component: NavLink,
            to: route('admin.post.index'),
        },
    },
]
```

Observe que a função `route` é usada para obter um caminho de rota a partir de seu nome. Após adicionar o trait `HasAdminSupport` ao modelo, as seguintes rotas estão disponíveis:

- WEB:
    - `admin.{model_name}.index`: A página do modelo
- API:
    - `admin.{model_name}.list`: Obter uma lista paginada de modelos
    - `admin.{model_name}.create`: Criar um novo modelo
    - `admin.{model_name}.item`: Obter um modelo por id
    - `admin.{model_name}.update`: Atualizar um modelo por id
    - `admin.{model_name}.delete`: Excluir um modelo por id

Se o modelo tiver o trait `SoftDeletes`, as seguintes rotas também estão disponíveis:

- API:
    - `admin.{model_name}.restore`: Restaurar um modelo por id
    - `admin.{model_name}.forceDelete`: Excluir definitivamente um modelo por id

Se você quiser personalizar as rotas, pode fazê-lo adicionando os seguintes métodos ao modelo:

```php
public function getWebUrls()
{
    return [
        'index' => 'custom/url/path',
        // use este array para criar rotas web adicionais, se desejar
    ];
}

public function getApiUrls()
{
    return [
        'list' => 'custom/url/to/posts',
        'item' => 'custom/url/to/posts/{id}',
        'create' => [
            'url' => 'custom/url/to/posts/create',
            'method' => 'post',
        ],
        'update' => [
            'url' => 'custom/url/to/posts/{id}/update',
            'method' => 'post',
        ],
        'delete' => [
            'url' => 'custom/url/to/posts/{id}/delete',
            'method' => 'delete',
        ],
    // Se o modelo tiver o trait SoftDeletes, adicione o seguinte também:
    //    'restore' => [
    //        'url' => 'custom/url/to/posts/{id}/restore',
    //        'method' => 'post',
    //    ],
    //    'forceDelete' => [
    //        'url' => 'custom/url/to/posts/{id}/force-delete',
    //        'method' => 'delete',
    //    ],
    ];
}
```

### Os modelos eloquentes do frontend

Depois que um modelo foi adicionado ao painel administrativo, um modelo frontend pode ser recuperado para essa classe. Este modelo frontend será usado para renderizar a página do modelo, criar e editar formulários e para manipular os dados do modelo. Para recuperar o modelo frontend, use o método `ModelRepository.getModelClass`:

```js
import { modelRepository } from '@arandu/laravel-mui-admin';

const Post = modelRepository.getModelClass('post'); // o parâmetro deve estar em snake case (ex: 'blog_post' para o modelo BlogPost)

// use a classe Post para criar um novo modelo
const post = new Post({
    title: 'Título do Post',
    content: 'Conteúdo do Post',
});

// você pode definir os atributos fluentemente
post.title = 'Novo título';

// salve o modelo
post.save().then(() => {
    // faça algo após salvar
});
```

Para buscar uma lista de modelos, você pode usar o `axios` juntamente com a função `route`:

```js
import axios from 'axios';

const postsResponse = await axios.get(route('admin.post.list'), {
    params: {
        page: 1,
        per_page: 10,
        // use este objeto para adicionar filtros
    },
});

// isso retornará uma lista de modelos Post
const posts = postsResponse.data.data.map((post) => new Post(post));
```

Alternativamente, se você estiver em um componente funcional, pode usar o hook `useFetchList`. Isso mapeará automaticamente a resposta para uma lista de modelos:

```jsx
import { useFetchList, modelRepository } from '@arandu/laravel-mui-admin';

const Post = modelRepository.getModelClass('post');

const Posts = () => {
    const { items: posts, request } = useFetchList(Post);

    const { loading, error } = request;

    if (loading) {
        return <div>Carregando...</div>;
    }

    if (error) {
        return <div>Erro: {error.message}</div>;
    }

    return (
        <div>
            {posts.map((post) => (
                <div key={post.id}>{post.title}</div>
            ))}
        </div>
    );
};
```

Este hook reflete e gerencia os parâmetros de pesquisa na URL. Por exemplo, se a URL for `/posts?q=foo`, o hook adicionará automaticamente o parâmetro `q` à solicitação.

Para obter uma documentação completa sobre os modelos de frontend, consulte a documentação do `@arandu/laravel-mui-admin`.

#### Nota sobre relacionamentos

Devido aos modelos de frontend, este pacote verifica cada relacionamento que existe no modelo "backend". Para fazer isso funcionar, os métodos de relacionamento devem ser definidos no modelo com dicas de tipo. Por exemplo:

```php
class Post extends Model
{
    public function user(): BelongsTo # ou : HasMany, : HasOne, etc.
    {
        return $this->belongsTo(User::class);
    }
}
```

Isso torna possível recuperar o modelo relacionado a partir do modelo de frontend quando o relacionamento está carregado. Por exemplo:

```js
const response = await axios.get(route('admin.post.item', { id: 1 }));
// a resposta.data será um modelo Post com o relacionamento user carregado
// {
//     id: 1,
//     title: 'Título do Post',
//     content: 'Conteúdo do Post',
//     user: {
//         id: 1,
//         name: 'John Doe',
//     },
// }

const post = new Post(response.data);

// o relacionamento user está carregado
const user = post.user;

// você pode trabalhar no modelo relacionado
user.name = 'Novo nome';
user.save();
```

### Personalização

O componente `RepositoryIndex` do pacote `@arandu/laravel-mui-admin` é responsável por renderizar a lista de modelos, lidar com paginação, filtros, ações, criar, editar e deletar modelos. Existem várias formas de personalizar a aparência deste componente. Esta documentação cobrirá o que pode ser feito no nível do backend para personalizar colunas, formulários, abas e a pesquisa. Para personalização no frontend, consulte a documentação do componente `RepositoryIndex`.

#### Personalizar as colunas da página do modelo

Por padrão, o componente `RepositoryIndex` renderizará uma tabela com colunas correspondentes à propriedade `$fillable` do modelo. Se você quiser personalizar as colunas, deve criar uma classe em seu projeto em `app/Admin/Tables/{$model}Table.php`. Por exemplo, se o modelo é chamado `Post`, a classe deve ser chamada `PostTable`.

A classe criada deve ter pelo menos um método chamado `default`, que será usado quando nenhum outro método for especificado. Por exemplo, se você quiser personalizar as colunas para o modelo `Post`, deve criar uma classe em `app/Admin/Tables/PostTable.php` com o seguinte conteúdo:

```php
<?php

namespace App\Admin\Tables;

class PostTable 
{
    public function default()
    {
        return [
            [
                // 'key' é o nome do atributo no modelo
                'key' => 'title',
                // 'label' é o texto que será exibido na coluna
                'label' => __('Title'),
            ],
            [
                // Você pode utilizar o ponto para acessar atributos aninhados
                'key' => 'author.name',
                'label' => __('Author Name'),
            ],
            [
                // Você pode criar colunas personalizadas para serem
                // gerenciadas posteriormente no frontend
                'key' => 'categories',
                'label' => __('Categories'),
            ]
        ];
    }
}
```

### Adicionando campos personalizados

Por padrão, o componente `RepositoryIndex` renderizará um formulário com campos correspondentes à propriedade `$fillable` do modelo, e todos os campos serão do tipo `text`. Se você quiser personalizar os campos, deve criar uma classe em seu projeto em `app/Admin/Forms/{$model}Form.php`. Por exemplo, se o modelo é chamado `Post`, a classe deve ser chamada `PostForm`. Esta classe deve estender a classe `Arandu\LaravelMuiAdmin\Contracts\Form` e deve ter pelo menos um método chamado `default`, que será usado quando nenhum outro método for especificado.


```php
<?php

namespace App\Admin\Forms;

use Arandu\LaravelMuiAdmin\Contracts\Form;

class PostForm extends Form
{
    public function default()
    {
        return [
            [
                // 'name' é o nome do atributo no modelo
                'name' => 'title',
                // 'label' é o texto que será exibido no campo
                'label' => __('Title'),
                // 'type' é o tipo do campo
                'type' => 'text',
                // parâmetros adicionais podem ser adicionados
                'required' => true,
            ],
            [
                'name' => 'content',
                'label' => __('Content'),
                'type' => 'textarea',
            ],
        ];
    }
}
```

### Adicionando abas personalizadas

As abas que aparecem na página devem ser personalizadas no frontend através do registro de um filtro usando o método `addFilter` do pacote `@arandu/laravel-mui-admin`. Verifique a documentação do componente `RepositoryIndex` para mais informações.

Para lidar com as consultas das abas, sobrescreva o método `scopeWhereBelongsToTab` no modelo. Por exemplo, se você quiser adicionar uma aba para mostrar apenas as postagens que estão publicadas, você deve adicionar o seguinte método ao modelo `Post`:

```php
public function scopeWhereBelongsToTab($query, $tab)
{
    if ($tab === 'published') {
        return $query->where('published', true);
    }

    return $query;
}
```

Por padrão o componente `RepositoryIndex` irá renderizar uma aba com o nome `all` que mostrará todos os modelos. Se o modelo possuir o trait `SoftDeletes`, uma aba com o nome `trashed` também será renderizada. Não esqueça de lidar com essas abas no método `scopeWhereBelongsToTab`.

### Adicionando busca

Para lidar com as consultas de busca, substitua o método `scopeSearch` no modelo. Por exemplo, se você quiser adicionar uma busca para encontrar posts pelo título, deve adicionar o seguinte método ao modelo `Post`:

```php
public function scopeSearch($query, $search)
{
    return $query->where('title', 'like', "%{$search}%");
}
```

### Aprofundando-se

Para mais informações, aguarde a documentação completa. Por enquanto, você pode verificar o código-fonte do pacote `@arandu/laravel-mui-admin` para ver o que pode ser feito.
