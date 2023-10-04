# Criando um CRUD

Nesta documentação, você aprenderá como criar um CRUD (Create, Read, Update, Delete) utilizando a estrutura do projeto e seus recursos.

## 1. Configurando o Model

Primeiro, você precisará adicionar o trait `HasCrudSupport` às Models que você deseja criar um CRUD. Este trait facilita a configuração do CMS e fornece funcionalidades úteis para simplificar o processo.

Para adicionar o trait `HasCrudSupport` à sua Model, siga o exemplo abaixo:

```php
use App\Contracts\HasCrudSupport;

class YourModel extends Model
{
    use HasCrudSupport;

    // ...
}
```

### Ajustando o trait `HasCrudSupport`

O trait `HasCrudSupport` fornece diversos métodos que você pode sobrescrever para personalizar a configuração do CRUD. Aqui estão alguns dos métodos mais importantes:

- `getFormFillable()`: Retorna os campos preenchíveis da Model.
- `getSchema()`: Retorna o schema da Model, incluindo os campos preenchíveis, as definições de campos e as URLs da web.
- `getSchemaName()`: Retorna o nome da Model em snake_case. Este nome será usado para identificar a entidade no frontend.
- `getFieldsDefinition()`: Retorna a definição dos campos para a Model. Aqui você pode personalizar como os campos serão exibidos no formulário do CRUD.
- `scopePermitted($query)`: Método do escopo para filtrar os registros permitidos para o usuário atual.
- `scopeSearch($query, $search)`: Método do escopo para aplicar filtros de pesquisa nos registros.
- `getWebUrls()`: Retorna as URLs da web para as páginas do CRUD.
- `getApiUrls()`: Retorna as URLs da API para as operações CRUD.
- `web($renderer = 'authenticated')`: Método para configurar as rotas da web.
- `api()`: Método para configurar as rotas da API.
- `validateForCreate(Request $request)`: Método para validar os dados antes de criar um novo registro.
- `validateForUpdate(Request $request)`: Método para validar os dados antes de atualizar um registro existente.

Você pode sobrescrever qualquer um desses métodos para personalizar o comportamento do seu CRUD conforme necessário.

## 2. Definir o schema de campos no backend e utilizá-los no frontend:

No backend, as definições dos campos do formulário são feitas no método `getFieldsDefinition` no trait `HasCrudSupport`. No frontend, para obter essas definições de formulário de um model, você pode utilizar o `ModelRepository`. 

As models no frontend são criadas usando uma factory, que se encontra no arquivo `resources/js/src/models/index.js`. Aqui está um exemplo de como criar as classes User e Role:

```js
import modelRepository from '../api/@core/services/ModelRepository';

export const User = modelRepository.makeModelClass('user');

export const Role = modelRepository.makeModelClass('role');
```

No frontend, você pode utilizar a instância do `ModelRepository` para obter o schema das models e acessar as definições de campos. No componente `ModelForm`, as definições de campos são obtidas usando o método `getClassSchema` do `ModelRepository`, como mostrado abaixo:

```js
const fields = React.useMemo(() => {
    if (debug) {
        console.log('ModelForm.fields', item.className, schema);
    }
    // console.log(modelRepository.getClassSchema(item.className));
    return modelRepository.getClassSchema(item.className).fields[schema];
}, [item.className, schema, debug]);
```

Com base nesses passos, você pode definir o schema de campos no backend e utilizá-los no frontend conforme necessário.
## 3. Utilizando o Componente ModelForm

O componente `ModelForm` facilita a criação de formulários com base nas definições de campos fornecidas pelo trait `HasCrudSupport`. A injeção dos dados no frontend é feita automaticamente através do `ModelRepository`, que obtém o schema da Model usando o método `getClassSchema`. Isso permite que você crie formulários dinâmicos sem ter que especificar manualmente a configuração dos campos.

### Exemplo de uso do componente ModelForm

Para utilizar o componente `ModelForm`, siga o exemplo abaixo:

```jsx
import React from 'react';
import ModelForm from 'path/to/ModelForm';
import YourModel from 'path/to/YourModel';

const MyForm = () => {
    const item = new YourModel(/* inicialização, se necessário */);

    return (
        <ModelForm item={item} />
    );
};

export default MyForm;
```

O componente `ModelForm` aceita as seguintes props:

- `item`: A instância da Model para a qual você deseja criar o formulário. Esta prop é obrigatória.
- `schema`: O nome do schema a ser usado para renderizar o formulário. Por padrão, será usado o schema 'default'.
- `debug`: Se `true`, habilita o modo de depuração, que exibirá informações adicionais no console.

Ao utilizar o componente `ModelForm`, ele irá automaticamente buscar as definições de campos da Model especificada e renderizar os campos de acordo. Além disso, ele irá lidar com a validação e envio do formulário.

### Atualizando a Model no frontend

Para atualizar a Model no frontend após a submissão do formulário, você pode utilizar os seguintes métodos fornecidos pela instância da Model:

- `fill(data)`: Preenche a Model com os dados fornecidos.
- `diff()`: Retorna a diferença entre o estado atual da Model e o estado anterior.
- `save()`: Salva a Model, realizando a operação de criação ou atualização conforme necessário.

Esses métodos são utilizados no componente `ModelForm` para lidar com a submissão do formulário e atualizar a Model no frontend.