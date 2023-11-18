# v0.4.x

## **@arandu/laravel-mui-admin** (JS) v0.4.0 // **arandu/laravel-mui-admin** (PHP) v0.4.0-beta-001

Reorganizados os arquivos do projeto de forma a simplificar e facilitar o entendimento do carregamento do React App e aberta a funcionalidade para criar "plugins" para o frontend.

Também foi removida a dependência do `redux` e `react-redux` do projeto, que agora é opcional.

A principal alteração para organização, foi a remoção da pasta `resources/js/src` e a movimentação dos arquivos para `resources/js`. Isso foi feito para que o projeto fique mais simples de manusear.

### Atualizando para a versão:

1. Garanta o backup dos seus arquivos em `resources/js` antes de prosseguir.
2. Mova todas as **pastas** em `resources/js/src` para `resources/js`. Se a IDE perguntar sobre atualizar o caminho dos imports, aceite esta ajuda.
3. Atualize os arquivos em `resources/js/*.js` (admin, guest, authenticated) para que correspondam à estrutura abaixo:

```js
import React from 'react';
import ReactDOM from 'react-dom';

import { app } from '@arandu/laravel-mui-admin';

import App from './components/App';
import config from './config';
import routes from './routes/admin'; // ou './routes/guest' ou './routes/authenticated'
import macros from './macros';

window.addEventListener('load', async () => {
    const { router, theme } = await app.withRoutes(routes)
        .withConfig(config)
        .withMacros(macros)
        .init();

    ReactDOM.render(
        <App
            router={router}
            theme={theme}
        />,
        document.getElementById('root'),
    );
});
```

4. O arquivo `resources/js/macros/index.js` deverá exportar como `default` uma função, que deverá ter em seu corpo todos os registros de macros que antes eram chamados diretamente no corpo do arquivo. Exemplo:

```js

// antes
// ... macros
addFilter('some-filter', () => {
    // ...
});
// ...

// ===================================
// depois
export default () => {
    // ... macros
    addFilter('some-filter', () => {
        // ...
    });
    // ...
};
```

Também será necessário remover a importação de `runCoreMacros` do `@arandu/laravel-mui-admin`. O package não exporta mais essa função pois sua chamada é realizada internamente no método `App.init()`. Exemplo:

```js
// antes
import { runCoreMacros, addFilter, addAction } from '@arandu/laravel-mui-admin';

runCoreMacros();

// ===================================
// depois
import { addFilter, addAction } from '@arandu/laravel-mui-admin';
// ...
```

5. Crie o componente `App` em `resources/js/components/App.js` com o seguinte conteúdo:

```js
import React from 'react';

import { ThemeProvider, CssBaseline } from '@mui/material';

import { ToastProvider, DialogProvider } from '@arandu/laravel-mui-admin';
import { RouterProvider } from 'react-router-dom';

const App = ({ router, theme }) => (
    <React.StrictMode>
        <ThemeProvider theme={theme}>
            <CssBaseline />
            <RouterProvider router={router} />
            <ToastProvider />
            <DialogProvider />
        </ThemeProvider>
    </React.StrictMode>
);

export default App;
```

 > Verifique o arquivo `resources/js/src/renderer.jsx` e inclua os providers necessários para o seu projeto no componente `App`. Se você estiver utilizando `redux`, inclua o `Provider` do `react-redux` e o `store` do `redux` no componente `App` de acordo com o observado em `resources/js/src/renderer.jsx`.

 > O Redux não faz mais parte do conjunto inicial do `arandu/laravel-mui-admin`, mas pode ser utilizado normalmente, sendo feita a instalação e configuração por conta do desenvolvedor.

6. Se o projeto não utilizar o redux, remova a pasta `resources/js/state`.

7. Apague a pasta `resources/js/src`.

8. Apague a pasta `resources/js/models`. Caso você esteja importando algum modelo desta pasta, substitua a importação pelo seguinte:

```js
// antes
import User from '../models/User';

// ===================================
// depois
import { modelRepository } from '@arandu/laravel-mui-admin';

const User = modelRepository.getModelClass('user');
```

 > O suporte a modelos customizados apresentava problemas, e não permitia que o desenvolvedor criasse métodos funcionais. Da forma que estava, era uma estrutura redundante e que provavelmente não tem utilidade no seu projeto. Caso a substituição acima não funcione, você pode manter a pasta `resources/js/models` e importar os modelos normalmente. Suporte a modelos customizados será reavaliado em versões futuras.

9. Apague o arquivo `resources/js/routes/index.js`.

10. Certifique-se de não estar importando mais nada de `resources/js/src`.

Neste ponto seu projeto deverá funcionar normalmente.

### Criando um plugin

A definição de plugin ainda é bastante primitiva, mas permite que faça algumas alterações no CMS através da aplicação de filtros ou ações.

Plugins serão fornecidos no carregamento do `app`, antes da chamada do método `init`, através do método `withPlugins`:

```js
const { router, theme } = await app.withRoutes(routes)
    .withConfig(config)
    .withMacros(macros)
    .withPlugins([MyPlugin, AnotherPlugin])
    .init();
```

Um plugin é um objeto que deve ter um método `macros`. Este método poderá registrar novas macros ou alterar as existentes. Exemplo:

```js
const MyPlugin = {
    macros: () => {
        addFilter('some-filter', () => {
            // ...
        });
    },
};
```

# v0.3.x

## **arandu/laravel-mui-admin** (PHP) v0.3.3

Refatorado o trait `HasAdminSupport`, dividindo-o em vários comportamentos menores. 

Atualizando para a versão:

Agora os filtros em tabelas do CMS aproveitam um dos subcomportamentos extraídos do `HasAdminSupport`. Então para relacionar um filtro a uma tabela, antes usávamos a propriedade `$filterForm`, que passa a ser chamado `$formClass`.

```php
<?php

class SomeModelTable extends Table
{
      // protected $filterForm = SomeForm::class;
      protected $formClass = SomeForm::class;
}
```

Essa propriedade também permite que se faça o apontamento de uma classe de formulário para um modelo, caso não seja seguida a convenção de local/nome de arquivo para o formulario

```php
<?php

class SomeModel extends Model
{
    protected $formClass = MyCustomForm::class;

    // também é possível apontar uma tabela
    protected $tableClass = MyCustomTable::class;
}
```

## **@arandu/laravel-mui-admin** (JS) v0.3.4

Agora os campos tipo "autocomplete" em formulários já são capazes de identificar automaticamente a model relacionada, dispensando o uso da propriedade "list"

```php

class Post extends Model {
    protected $fillable = [/* ... */, 'author_id'];

    // ...
    public function author(): BelongsTo
    {
         return $this->belongsTo(User::class); // a model associada está descrita na relação
    }
}

class PostForm extends Form {
    public function default() {
        return [
            // outros campos...
            [
                 'name' => 'author',
                 'type' => 'autocomplete',
                 'label' => 'Autor',
                 // 'list' => 'user' <- essa linha não é mais necessária, a nao ser que seja para utilizar uma closure como abaixo:
                 // 'list' => function ($search) {
                 //      return User::withOnly([])->role('author')->search($search)->limit(30)->get(['id', 'name']);
                 //  }
            ],
        ];
   }
}
```
