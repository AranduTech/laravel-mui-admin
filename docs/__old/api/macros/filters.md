# Introdução

Bem-vindo à documentação de filtros para o pacote Laravel MUI Admin. Os filtros são uma parte integral deste pacote, fornecendo uma maneira poderosa de personalizar e estender a funcionalidade do CMS.

Nesta documentação, abordaremos os seguintes filtros:

- `repository_index_tabs`: Personalize as abas no componente `RepositoryIndex`.
- `repository_index_get_item_actions`: Personalize as ações disponíveis para cada item no componente `RepositoryIndex`.
- `repository_index_item_data`: Personalize os dados de cada item no componente `RepositoryIndex`.
- `repository_index_get_mass_actions`: Personalize as ações em massa disponíveis para cada item no componente `RepositoryIndex`.

Cada filtro inclui uma descrição de sua finalidade, os parâmetros que aceita e um exemplo de código demonstrando seu uso. Vamos começar!

# Entendendo a Estrutura de um Filtro

Um filtro é um tipo de função que processa uma entrada e produz uma saída, permitindo a personalização e a manipulação dos dados no Laravel MUI Admin. Cada filtro é composto por um conjunto de parâmetros e um *callback*, que é a função que será executada quando o filtro for chamado.

Um ponto crucial para entender é que o *callback* de um filtro deve **sempre** retornar um valor. Isso ocorre porque o objetivo principal de um filtro é modificar ou transformar um valor de entrada, produzindo um novo valor que será usado posteriormente no sistema. Se o callback não retornar um valor, isso pode resultar em um comportamento inesperado, pois o sistema pode receber um valor `undefined` onde espera um valor específico.

Outra questão importante ao trabalhar com filtros é a de evitar efeitos colaterais ao modificar o valor de entrada. Efeitos colaterais ocorrem quando a execução de uma função altera o estado fora de seu escopo. No contexto dos filtros, um efeito colateral ocorreria se você modificasse diretamente o valor de entrada, em vez de retornar um novo valor baseado na entrada.

Em JavaScript, variáveis de objetos e arrays são tratadas como referências. Isso significa que se você modificar um array ou objeto diretamente, você está modificando a referência original, o que pode afetar outras partes do código que também se referem a esse objeto ou array. Isso pode levar a erros sutis e difíceis de rastrear. Para evitar esses tipos de efeitos colaterais, você deve sempre criar uma nova cópia do valor que deseja modificar e trabalhar com essa cópia.

Por exemplo, se você estiver trabalhando com um array, você pode usar o operador spread (`...`) para criar uma nova cópia do array antes de modificar. Isso garante que o array original permaneça inalterado, evitando potenciais efeitos colaterais. O novo array, agora modificado, é o que deve ser retornado pelo callback do filtro.

Aqui está um exemplo de como isso pode ser feito, considerando um filtro que modifica um array:

```javascript
macros.addFilter('nome_do_filtro', (array) => {
  if (className === 'MeuModelo') {
    // Cria uma nova cópia do array com os itens do array original
    // e adiciona um novo item ao final do array.
    return [...array, { foo: 'bar' }];
  }
  return array;
});
```

Neste exemplo, `...array` cria uma nova cópia do array `array` e o novo elemento é adicionado a esta cópia. O array original `array` permanece inalterado, prevenindo qualquer efeito colateral. 

Ao trabalhar com filtros, sempre tenha em mente a importância de retornar um valor e evitar efeitos colaterais. Essas práticas irão garantir que seus filtros sejam previsíveis e seguros para uso em qualquer parte do sistema.

### `repository_index_tabs`

Esta macro é usada para personalizar as abas no componente `RepositoryIndex`. Ela é aplicada em um array vazio por padrão, e o nome da classe do modelo é passado como parâmetro.

#### Parâmetros:

- `tabs`: Um array de objetos de abas. Cada objeto de aba deve ter uma propriedade `name` e `label`.
- `className`: O nome da classe do modelo para o qual as abas estão sendo criadas.

#### Exemplo:

```javascript
macros.addFilter('repository_index_tabs', (tabs, className) => {
  if (className === 'MeuModelo') {
    return [
      ...tabs, 
      { name: 'minhaAba', label: 'Minha Aba Personalizada' }
    ];
  }
  return tabs;
});
```

### `repository_index_get_item_actions`

Esta macro é usada para personalizar as ações disponíveis para cada item no componente `RepositoryIndex`. Ela é aplicada em um array vazio por padrão, e o item para o qual as ações estão sendo criadas é passado como parâmetro.

#### Parâmetros:

- `actions`: Um array de objetos de ação. Cada objeto de ação deve ter uma propriedade `name`, `label` e uma função `onClick`.
- `item`: O item para o qual as ações estão sendo criadas.

#### Exemplo:

```javascript
macros.addFilter('repository_index_get_item_actions', (actions, item) => {
  if (item.name === 'meuItem') {
    return [
      ...actions, 
      {
        name: 'minhaAcao',
        label: 'Minha Ação Personalizada',
        onClick: (item) => { console.log(`Ação personalizada para o item: ${item.id}`); },
      }
    ];
  }
  return actions;
});
```

### `repository_index_item_data`

Esta macro é usada para personalizar os dados de cada item no componente `RepositoryIndex`. Ela é aplicada no valor acessado do item usando a chave da coluna, e o próprio item e a coluna são passados como parâmetros adicionais.

#### Parâmetros:

- `value`: O valor acessado do item usando a chave da coluna.
- `item`: O item para o qual os dados estão sendo personalizados.
- `column`: A coluna para a qual os dados estão sendo personalizados.

#### Exemplo:

```javascript
macros.addFilter('repository_index_item_data', (value, item, column) => {
  if (column.key === 'minhaColuna') {
    return value.toUpperCase();
  }
  return value;
});
```

### `repository_index_get_mass_actions`

Esta macro é usada para personalizar as ações em massa disponíveis para cada item no componente `RepositoryIndex`. Ela é aplicada em um array vazio por padrão, e o nome da classe do modelo e a aba atual são passados como parâmetros.

#### Parâmetros:

- `actions`: Um array de objetos de ação. Cada objeto de ação deve ter uma propriedade `name` e `label`.
- `className`: O nome da classe do modelo para o qual as ações estão sendo criadas.
- `tab`: A aba atual para a qual as ações estão sendo criadas.

#### Exemplo:

```javascript
macros.addFilter('repository_index_get_mass_actions', (actions, className, tab) => {
  if (className === 'MeuModelo' && tab === 'minhaAba') {
    actions.push({ name: 'minhaAcao', label: 'Minha Ação Personalizada' });
  }
  return actions;
});
```

