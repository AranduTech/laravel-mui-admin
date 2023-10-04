# Gerenciamento de Dados: Comunicação com a API e CRUD

## Introdução

Neste guia, você aprenderá como gerenciar dados no projeto utilizando a comunicação com a API do Laravel e operações de CRUD (Create, Read, Update, Delete). Abordaremos a estrutura básica para lidar com requisições à API e como integrar isso com o gerenciamento de estado global.

## Comunicação com a API

Para facilitar a comunicação com a API do Laravel, foi criado o `ApiClient`. O `ApiClient` é uma classe que encapsula o Axios e configura automaticamente as credenciais e o token CSRF para requisições à API interna do Laravel. Ele está localizado em `resources/js/src/api/@core/services/ApiClient.js`.

Para utilizar o `ApiClient`, importe-o em seus componentes ou arquivos e use-o para realizar chamadas à API. Veja um exemplo abaixo de como consumir o `ApiClient`:

```javascript
import ApiClient from '../api/@core/services/ApiClient';

// Realizando uma requisição GET
ApiClient.request({
    method: 'get',
    route: 'api.posts',
}).then(response => {
    console.log(response.data);
}).catch(error => {
    console.error(error);
});

// Realizando uma requisição POST
ApiClient.request({
    method: 'post',
    route: 'api.posts.store',
    data: {
        title: 'New Post',
        content: 'This is a new post.',
    },
}).then(response => {
    console.log(response.data);
}).catch(error => {
    console.error(error);
});
```

## Operações de CRUD

As operações de CRUD são facilitadas pelo CMS do painel do administrador, que, quando configurado corretamente, terá todas as operações disponíveis. A seguir, apresentamos uma visão geral das operações de CRUD. Detalhes específicos e exemplos de código para cada operação serão abordados em uma documentação específica.

### Create (Criação)

Para criar um novo registro, você pode enviar uma requisição `POST` para o endpoint relevante da API.

### Read (Leitura)

Para ler registros existentes, você pode enviar uma requisição `GET` para o endpoint relevante da API.

### Update (Atualização)

Para atualizar um registro existente, você pode enviar uma requisição `PUT` ou `PATCH` para o endpoint relevante da API.

### Delete (Exclusão)

Para excluir um registro existente, você pode enviar uma requisição `DELETE` para o endpoint relevante da API.

A documentação específica para cada operação de CRUD fornecerá exemplos de código e explicações detalhadas sobre como implementar essas operações no contexto do seu projeto.