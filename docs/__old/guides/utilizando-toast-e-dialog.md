# Utilizando `Toast` e `Dialog`

O Material UI fornece elementos para exibir mensagens e alertas para o usuário. O projeto inclui implementações de `Toast` e `Dialog` que facilitam o uso desses elementos. Neste guia, você aprenderá como utilizar esses elementos.

## Utilizando `Toast`

O `Toast` é um elemento que exibe uma mensagem temporária para o usuário. Para utilizá-lo, você precisará importar o módulo `api`:

```jsx
import api from '../api'; // aponta para resources/js/src/api/index.js
// ...
<Button 
    onClick={() => {
        api.toast.create('Mensagem de exemplo', 'success', 5000);
    }}
>
    Criar Toast
</Button>
```

O método `create` recebe três parâmetros:

- `message`: mensagem a ser exibida;
- `type`: tipo da mensagem. Pode ser `success`, `error`, `info` ou `warning`;
- `duration`: duração da mensagem, em milissegundos.

Pode-se utilizar também os métodos `success`, `error`, `info` e `warning` para criar um `Toast` com o tipo já definido:

```jsx
api.toast.success('Mensagem de sucesso');
api.toast.error('Mensagem de erro');
api.toast.info('Mensagem de informação');
api.toast.warning('Mensagem de alerta');
```

> **Obs**: Para que o `Toast` seja exibido, é necessário que o componente `ToastProvider` esteja presente na árvore de componentes. Recomendamos que seja adicionado no arquivo `resources/js/src/renderer.jsx`. Nas renderizações `guest` e `authenticated`, o `ToastProvider` já está presente.

## Utilizando `Dialog`

O `Dialog` é um elemento que exibe uma mensagem para o usuário e pode solicitar que ele confirme ou cancele a ação. É um substituto para as funções `alert` e `confirm` do navegador, utilizando `Promise` para lidar com o resultado. Para utilizá-lo, você precisará importar o módulo `api`:

```jsx
import api from '../api'; // aponta para resources/js/src/api/index.js

// ...
<Button 
    onClick={() => {
        api.dialog.create({
            title: 'Título do dialog',
            message: 'Confirma a ação?',
            dismissable: false,
            confirmText: 'Confirmar',
            cancelText: 'Cancelar',
            type: 'confirm',
        }).then((response) => {
            if (response) {
                // Confirmação
                return;
            }
            // Cancelamento
        });
    }}
>
    Criar Dialog
</Button>

```

O método `create` recebe um objeto com as seguintes propriedades:

- `message`: mensagem a ser exibida - Obrigatório;
- `title`: título da mensagem. Se não for informado, o título será omitido;
- `dismissable`: indica se o dialog pode ser fechado clicando fora dele. Padrão: `true`;
- `confirmText`: texto do botão de confirmação. Padrão: `Ok`;
- `cancelText`: texto do botão de cancelamento - somente para tipo `confirm`. Padrão: `Cancelar`;
- `type`: tipo do dialog. Pode ser `alert` ou `confirm`. Padrão `alert`.

Pode-se utilizar também os métodos `alert` e `confirm` para criar um `Dialog` com o tipo já definido:

```jsx
api.dialog.alert('Dados salvos com sucesso!').then(() => {
    console.log('Alerta fechado');
});

api.dialog.confirm('Deseja realmente excluir o registro?').then((response) => {
    if (response) {
        // Confirmação
        return;
    }
    // Cancelamento
});
```
> **Obs**: Para que o `Dialog` seja exibido, é necessário que o componente `DialogProvider` esteja presente na árvore de componentes. Recomendamos que seja adicionado no arquivo `resources/js/src/renderer.jsx`. Nas renderizações `guest` e `authenticated`, o `DialogProvider` já está presente.
