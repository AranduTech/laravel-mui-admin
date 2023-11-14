# Laravel MUI Admin

Um CMS orientado a modelos, altamente personalizável para Laravel 8.x, com um conjunto de ferramentas fullstack para desenvolvimento ágil de aplicações web.

Construído com [Material-UI](https://mui.com/material-ui) e [React](https://react.dev/)

[English docs](README_en.md)

 > **Nota:** Este pacote ainda está em desenvolvimento. Podem ocorrer mudanças significativas na API até a versão 1.0.0.

## Template

Disponibilizamos um template para iniciar um projeto com este pacote. [Clique aqui]({{LINK AQUI}})

Caso deseje realizar a instalação manual, veja a [documentação de instalação](./docs/instalacao.md)

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

## Criar as permissões para novos modelos

Durante o desenvolvimento, você pode criar novos modelos que precisam de permissões para serem acessados. Para atualizar a lista de permissões da aplicação, execute o seguinte comando:

```bash
php artisan admin:permissions
```

## Criar um novo usuário administrador

Para criar um novo usuário administrador, execute o seguinte comando:

```bash
php artisan admin:credentials
```

Este comando perguntará por um nome de usuário, e-mail e senha, e criará um novo usuário com o papel de `admin`.

## Preparando um modelo para o painel administrativo

Para preparar um modelo para o painel administrativo, você precisa adicionar o trait `HasAdminSupport` a ele. Esse trait adicionará os métodos necessários ao modelo para fazê-lo funcionar com o painel administrativo.

Além disso, você deve ter a propriedade `$fillable` configurada no modelo. Esta propriedade é usada para informar ao frontend quais campos podem ser preenchidos, e também é usada para renderizar as colunas da página do modelo. Você pode personalizar os campos e as colunas, conforme veremos adiante.

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

    // use este array para criar rotas api adicionais, se desejar
    // porém será necessário criar os métodos correspondentes no controller
    // e registrar a controller na configuração 'admin.cms.controller_overrides'
    ];
}
```

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

Isso desbloqueará algumas funcionalidades, como sincronização de relacionamentos, e também permitirá que o frontend carregue os modelos relacionados automaticamente.

### Personalização

#### Personalizar as colunas da página do modelo

Por padrão, o componente `RepositoryIndex` renderizará uma tabela com colunas correspondentes à propriedade `$fillable` do modelo. Se você quiser personalizar as colunas, deve criar uma classe em seu projeto em `app/Admin/Tables/{$model}Table.php`. Por exemplo, se o modelo é chamado `Post`, a classe deve ser chamada `PostTable`.

A classe criada deve ter pelo menos um método chamado `default`, que será usado quando nenhum outro método for especificado. Por exemplo, se você quiser personalizar as colunas para o modelo `Post`, deve criar uma classe em `app/Admin/Tables/PostTable.php` com o seguinte conteúdo:

```php
<?php

namespace App\Admin\Tables;

use Arandu\LaravelMuiAdmin\Contracts\Table;

class PostTable extends Table
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

 > Caso deseje criar uma classe em outro local, ou com outro nome, será necessário adicionar a propriedade `$tableClass` no modelo. Exemplo: `protected $tableClass = 'App\\Tables\\PostTableWithCustomName';`

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
                // 'type' é o tipo do campo, por padrão é 'text'
                'type' => 'text',
                // parâmetros adicionais serão passados para o componente
                'required' => true,
            ],
            [
                'name' => 'content',
                'label' => __('Content'),
                'multiline' => true,
            ],

            // Você pode relacionar modelos com o 
            // campo tipo 'autocomplete'. Para isso,
            // o relacionamento deve estar definido
            // com dicas de tipo, como descrito acima.
            // Ex: relação tipo `BelongsTo` com uma model 
            // `User` chamada `author`. 
            // Também será necessário que
            // a chave estrangeira (ex: `author_id`)
            // esteja no array `$fillable` do modelo Post.
            [
                'name' => 'author',
                'label' => __('Author'),
                'type' => 'autocomplete',
                // O autocomplete irá listar itens
                // da model relacionada, por padrão.

                // É possível também fornecer resultados personalizados
                // para a listagem do autocomplete, ex:
                // 'list' => function ($search) {
                //    return User::role('author')->search($search)->get(['id', 'name']);    
                // }

            ]
        ];
    }
}
```

 > Caso deseje criar uma classe em outro local, ou com outro nome, será necessário adicionar a propriedade `$formClass` no modelo. Exemplo: `protected $formClass = 'App\\Forms\\PostFormWithCustomName';`

### Adicionando abas personalizadas

As abas que aparecem na página devem ser personalizadas no frontend através do [registro de um filtro](./docs/__old/api/macros/filters.md#repository_index_tabs) usando o método `addFilter` do pacote `@arandu/laravel-mui-admin`.

Para lidar com as consultas das abas, sobrescreva o método `scopeWhereBelongsToTab` no modelo. Por exemplo, se você quiser adicionar uma aba para mostrar apenas as postagens que estão publicadas, você deve adicionar o seguinte método ao modelo `Post`:

```php
public function scopeWhereBelongsToTab($query, $tab)
{
    if ($tab === 'published') {
        $query->where('published', true);
    }
}
```

Por padrão o componente `RepositoryIndex` irá renderizar uma aba com o nome `all` que mostrará todos os modelos. Se o modelo possuir o trait `SoftDeletes`, uma aba com o nome `trashed` também será renderizada. Não esqueça de lidar com essas abas no método `scopeWhereBelongsToTab`.

### Adicionando busca

A implementação padrão da pesquisa realiza uma consulta utilizando `"LIKE"` em todos os campos da propriedade `$fillable`. Se você quiser personalizar a pesquisa, deve sobrescrever o método `scopeSearch` no modelo. Por exemplo, se você quiser pesquisar apenas pelo título ou pelo nome do autor, você deve adicionar o seguinte método ao modelo `Post`:

```php
public function scopeSearch($query, $search)
{
    $query->where(function ($query) use ($search) {
        $query->where('title', 'like', "%{$search}%");
        $query->orWhereHas('author', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
    });
}
```

### Aprofundando-se

Para mais informações, aguarde a documentação completa. Por enquanto, você pode verificar o código-fonte deste pacote e do pacote (`@arandu/laravel-mui-admin`)[https://github.com/AranduTech/react-laravel-mui-admin] para ver o que pode ser feito.
