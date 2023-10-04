# Criando Componentes

## Introdução

Neste guia, você aprenderá como criar componentes no frontend do projeto. Este documento assume que você já possui conhecimento sobre o framework React.

## Criando um componente

Os componentes são blocos de construção reutilizáveis em uma aplicação React. Eles são criados na pasta `resources/js/src/components`. Para criar um componente, crie um arquivo JSX na pasta mencionada. Por exemplo, vamos criar um componente chamado `ExampleComponent`:

```jsx
import React from 'react';

const ExampleComponent = () => {
  return <div>Example Component</div>;
};

export default ExampleComponent;
```

## Utilizando `artisan make:react-component`

O projeto possui um comando `artisan` para facilitar a criação de componentes. Para utilizá-lo, você precisará executar o comando `php artisan make:react-component` e passar o nome do componente a ser criado. O comando aceita as opções `--prop-types` e `--connected`, que serão detalhadas a seguir.

Por exemplo, vamos criar um componente chamado `ExampleComponent`:

```bash
php artisan make:react-component ExampleComponent
```

O comando irá criar o arquivo `ExampleComponent.jsx` na pasta `resources/js/src/components`.

### Opção `--prop-types`

Se você desejar criar um componente com PropTypes definidos, utilize a opção `--prop-types`:

```bash
php artisan make:react-component ExampleComponent --prop-types
```

O componente será criado com o espaço para criar os PropTypes:

```jsx
import React from 'react';
import PropTypes from 'prop-types';

const ExampleComponent = (props) => {
  return <div>{props.exampleProp}</div>;
};

ExampleComponent.propTypes = {
  exampleProp: PropTypes.string,
};

export default ExampleComponent;
```

### Opção `--connected`

Se você desejar criar um componente conectado ao Redux, utilize a opção `--connected`:

```bash
php artisan make:react-component ExampleComponent --connected
```

O componente será criado com a conexão ao Redux:

```jsx
import React from 'react';
import { connect } from 'react-redux';

const ExampleComponent = (props) => {
  return <div>{props.exampleProp}</div>;
};

const mapStateToProps = (state) => ({
  exampleProp: state.exampleProp,
});

export default connect(mapStateToProps)(ExampleComponent);
```

Você também pode combinar ambas as opções:

```bash
php artisan make:react-component ExampleComponent --prop-types --connected
```

O componente será criado com PropTypes e conexão ao Redux:

```jsx
import React from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';

const ExampleComponent = (props) => {
  return <div>{props.exampleProp}</div>;
};

ExampleComponent.propTypes = {
  exampleProp: PropTypes.string,
};

const mapStateToProps = (state) => ({
  exampleProp: state.exampleProp,
});

export default connect(mapStateToProps)(ExampleComponent);
```
