# Criando uma renderização

## Introdução

Entende-se por uma renderização uma raiz em que será renderizada uma árvore de componentes React. Dentro de uma renderização, é possível criar várias páginas a partir de rotas caso a renderização utilize o React Router.

## Renderizações incluídas

Por padrão, o projeto possui três renderizações: `guest`, `authenticated` e `admin`. A renderização `guest` é utilizada para páginas que não necessitam de autenticação, como a página de login. A renderização `authenticated` é utilizada para páginas que necessitam de autenticação, como a edição do perfil. A renderização `admin` é utilizada para o CMS, disponível apenas para usuários com papel de Administradores.

## Devo criar uma nova renderização?

As renderizações incluídas no projeto utilizam o React Router, o que significa que é possível expandir o projeto com novas páginas. Crie uma nova renderização caso deseje, por exemplo, criar uma aplicação totalmente separada para cada tipo de usuário, por exemplo, em um blog, uma área para autores e uma área para inscritos. Isso afetará o tamanho do bundle final, entregando ao usuário apenas o que ele precisa.

Criar uma renderização nova é uma tarefa simples, mas requer um pouco mais de conhecimento sobre o projeto. Se você não tem certeza se deve criar uma nova renderização, não se preocupe, é possível expandir o projeto com novas páginas sem criar uma nova renderização.

## Adicionar uma nova renderização

A renderização inicializa-se por um arquivo na pasta `resources/js`, que deverá estar listado no `webpack.mix.js`. Também será necessário um arquivo blade na pasta views e configurar rotas web para acessá-la. Imagine que desejamos criar uma renderização nova e chamá-la de `newRenderer`. Para isso, modifique o arquivo `webpack.mix.js` da seguinte forma:

 - `webpack.mix.js`

```js
mix
// ...
   .js('resources/js/newRenderer.js', 'public/js')
   .react()
// ...
```

Depois, crie o arquivo `resources/js/newRenderer.js` com o seguinte conteúdo:

```js
import './src/api/@core/bootstrap';
import createApp from './src/app';

createApp('newRenderer');
```

Crie o arquivo blade para a renderização na pasta `resources/views`:

 - `resources/views/new-renderer.blade.php`
```blade
@extends('layouts.app')

@section('script')
<script src="{{ mix('js/newRenderer.js') }}" defer></script>
@endsection
```

Crie uma rota para a renderização no arquivo `routes/web.php`. Adicione todas as rotas que utilizarão esta renderização:

```php
Route::get('/newRenderer', function (\App\Services\React $react) {

    // injetar variáveis na view
    $react->set('foo', ['bar' => 'baz']);

    return view('new-renderer')->with(['react' => $react]);
});
```

Agora, modifique o arquivo `resources/js/src/renderer.jsx` e crie uma chave para a renderização. Adicione os providers que desejar, por exemplo o `ThemeProvider` e o `ReduxProvider`, e encapsule o componente raiz:

```jsx
import React from 'react';
import ReactDOM from 'react-dom';

import { Provider as ReduxProvider } from 'react-redux';
import { ThemeProvider, createTheme } from '@mui/material';

import App from './views/App';

import api from './api';

const theme = createTheme(api.config.theme);

export default {
    // ...
    newRenderer: (rootElement) => {
        ReactDOM.render(
            <React.StrictMode>
                <ReduxProvider store={api.state.store}>
                    <ThemeProvider theme={theme}>
                        <App />
                    </ThemeProvider>
                </ReduxProvider>
            </React.StrictMode>,
            rootElement
        );
    }
}
```

Crie o componente `App` na pasta `resources/js/src/views`:

 - `resources/js/src/views/App.jsx`
```jsx
import React from 'react';

const App = () => (
    <h1>Olá, mundo!</h1>
);

export default App;
```

Agora, a renderização `newRenderer` está pronta para ser utilizada. Para acessá-la, basta acessar a rota `/newRenderer` no navegador.

## Utilizando o React Router dentro de uma renderização

Para utilizar o React Router, primeiro crie um arquivo de rotas em `resources/js/src/routes`. Por exemplo, crie o arquivo `resources/js/src/routes/newRenderer.jsx`:

 - `resources/js/src/routes/newRenderer.jsx`
```jsx
import React from 'react';

import Suspense from '../api/@core/components/Suspense';
import Error from '../views/Error';

export default [
    {
        path: '/newRenderer',
        element: (
            <Suspense>
                {React.lazy(() => import('../views/App'))}
            </Suspense>
        ),
        errorElement: <Error />,
    },
    {
        path: '/otherRoute',
        element: (
            <Suspense>
                {React.lazy(() => import('../views/OtherRoute'))}
            </Suspense>
        ),
    }
];
```

Registre as rotas no arquivo `resources/js/src/routes/index.js`:

```js
import newRenderer from './newRenderer';

/**
 * Registra as rotas para cada renderização
 */ 
const routers = {
    // ...
    newRenderer,
};
// ...
```

Modifique o arquivo `resources/js/src/renderer.jsx` para utilizar o React Router:

```jsx
import { RouterProvider } from 'react-router-dom';

import createRouter from './routes';

export default {
    // ...
    newRenderer: (rootElement) => {
        ReactDOM.render(
            <React.StrictMode>
                <ReduxProvider store={api.state.store}>
                    <ThemeProvider theme={theme}>
                        <RouterProvider router={createRouter('newRenderer')} />
                    </ThemeProvider>
                </ReduxProvider>
            </React.StrictMode>,
            rootElement
        );
    }
}
```

Pronto! Agora, é possível acessar as rotas definidas no arquivo `resources/js/src/routes/newRenderer.jsx` através da renderização `newRenderer`.

> [OBS] O React Router criará apenas páginas acessíveis por navegação SPA. Para garantir que a página possa ser acessada diretamente pelo navegador, é necessário criar uma rota no arquivo `routes/web.php` para cada rota definida no arquivo `resources/js/src/routes/newRenderer.jsx`.
