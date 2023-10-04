# Criando Páginas e Rotas

## Introdução

Neste guia, abordaremos como criar páginas no frontend do projeto, assumindo que você já possui conhecimentos suficientes sobre React e React Router. Se você não conhece, recomendamos que você leia a documentação deles antes de continuar.

## Criando uma página

Para criar uma página, crie um arquivo na pasta `resources/js/src/views`. Por exemplo, vamos criar uma página chamada `ExamplePage`:

```jsx
import React from 'react';

const ExamplePage = () => {
  return <div>Example Page</div>;
};

export default ExamplePage;
```

## Utilizando `artisan make:react-component`

O projeto possui um comando `artisan` para facilitar a criação de páginas. Para utilizá-lo, você precisará executar o comando `php artisan make:react-component` com a option `--page` e passar o nome da página a ser criada. Por exemplo, vamos criar uma página chamada `ExamplePage`:

```bash
php artisan make:react-component ExamplePage --page
```

O comando irá criar o arquivo `ExamplePage.jsx` na pasta `resources/js/src/views`.

### Opções adicionais

O comando `make:react-component` aceita opções adicionais que podem ser úteis ao criar componentes. As opções são:

- `--prop-types`: Se passada essa opção, o comando criará o componente com o espaço para criar os PropTypes do componente.
- `--connected`: Se passada essa opção, o componente será criado com conexão ao react-redux.

## Criando a rota

Para acessar a página, você precisará criar uma rota para ela, dentro de uma renderização existente. Para isso, você precisará modificar o arquivo de rotas na pasta `resources/js/src/routes`. Por exemplo, vamos criar uma rota para a página `ExamplePage` dentro da renderização `guest`:

- `resources/js/src/routes/guest.jsx`
```jsx
export default [
   // ...
  {
    path: '/example',
    component: (
        <Suspense>
            {React.lazy(() => import('../views/ExamplePage')))}
        </Suspense>
    ),
  },
];
```

> **Obs**: Caso deseje que a página apareça dentro da estrutura de Layout da renderização, adicione a rota dentro do array `children` da rota raiz.

Adicione também a rota ao Laravel, no arquivo `routes/web.php`:

```php
Route::get('/example', function () {
    return view('guest');
});
```

## Utilizando rotas nomeadas

Para facilitar a navegação entre as páginas, você pode utilizar rotas nomeadas. Para isso, você precisará adicionar um nome para a rota, no arquivo de rotas:

- `routes/web.php`
```php
Route::get('/example', function (\App\Services\React $react) {
    return view('guest')->with(['react' => $react]);
})->name('example');
```

Ao injetar o serviço `React` no controller e enviar para a view, você poderá utilizar a função global `route` para gerar a URL da rota a partir do nome:

```jsx
<Link to={route('example')}>
    Go To Example Page
</Link>
```

A função `route` também aceita parâmetros, que serão substituídos na URL:

```jsx
// route 'post.item': post/{id}
console.log(route('post.item', { id: 1 })); // /post/1

// route 'comment.item': post/{postId}/comment/{id}
console.log(route('comment.item', { postId: 1, id: 2 })); // /post/1/comment/2
```

Ao registrar uma rota no React Router utilizando uma rota nomeada com parâmetros, a função `route` gera automaticamente a URL da rota a partir do nome, substituindo o formato `{param}` por `:param` se o segundo argumento for omitido:

```jsx
{
  // routes/web.php: route name: post.item | post/{id}
  path: route('post.item'), // /post/:id
  component: (
      <Suspense>
          {React.lazy(() => import('../views/PostItemPage')))}
      </Suspense>
  ),
},
```

Isso simplifica o processo de criação de rotas com parâmetros e garante que você possa se concentrar em desenvolver a funcionalidade do aplicativo, em vez de lidar com a formatação das rotas.

Agora você possui uma base sólida sobre como criar páginas e configurar rotas no projeto. A partir daqui, você pode continuar a criar componentes e expandir a funcionalidade do seu aplicativo. Lembre-se de consultar a documentação do React e React Router para obter informações adicionais e melhores práticas ao trabalhar com essas ferramentas.
