# Documentação do Hook useForm

Este Hook é usado para criar formulários com validação e controle de estado. Ele suporta validação de entrada de dados, submissão de formulário, tratamento de erros, e pode ser usado em conjunto com uma API para enviar os dados do formulário.

## Parâmetros

O Hook useForm aceita dois parâmetros: um objeto de opções e um array de dependências.

### Opções

As opções possíveis são:

- `initialValues` (objeto): Define os valores iniciais do formulário. Este objeto não deve ser alterado durante a execução do Hook. Alterações no estado do formulário devem ser feitas através da função `setProp` retornada pelo Hook.
  
- `onSubmit` (função): Função a ser executada ao submeter o formulário. Esta função é executada antes da requisição HTTP, e recebe como parâmetro o estado atual do formulário. Se esta função retornar `false`, a requisição HTTP não será executada.

- `onSuccess` (função): Função a ser executada quando a requisição HTTP for bem sucedida. Esta função recebe como parâmetro o retorno da requisição.

- `validate` (função): Função de validação do formulário.

- `validateOnInputChange` (boolean): Se verdadeiro, verifica os erros a cada input.

- `onChange` (função): Função a ser executada a cada input do formulário.

- `debug` (boolean): Se verdadeiro, exibe as informações no console.

- `onError` (função): Função a ser executada quando houver um erro durante a requisição HTTP. Esta função recebe como parâmetro o erro retornado pela requisição.

- `preventDefault` (boolean): Se verdadeiro, previne o comportamento padrão do formulário.

- `action` (string): URL para onde o formulário será enviado. Se não for passado, o formulário não fará requisição HTTP e apenas executará a função `onSubmit`.

- `method` (string): Método HTTP para envio do formulário. O valor padrão é `get`.

### Dependências

As dependências são um array de variáveis que, quando alteradas, farão com que o Hook atualize as opções.

## Retorno

O Hook retorna um objeto com as seguintes propriedades:

- `state`: Um array com dois elementos. O primeiro é o estado atual do formulário, e o segundo é a função `setProp`, que pode ser usada para alterar o estado do formulário.

- `errors`: Um array com os erros atuais do formulário.

- `inputProps`: Função para gerar as props de um input de texto. Recebe como argumentos a chave do input e uma função opcional para sanitizar o valor do input.

- `formProps`: Função para gerar as props do formulário.

- `checkProps`: Função para gerar as props de um checkbox. Recebe como argumentos a chave do checkbox e uma função opcional para sanitizar o valor do checkbox.

- `autocompleteProps`: Função para gerar as props de um autocomplete. Recebe como argumentos a chave do autocomplete e um objeto de opções.

## Exemplo de uso com componentes do Material UI

```jsx
import React from "react";
import Button from '@mui/material/Button';
import Checkbox from '@mui/material/Checkbox';
import Autocomplete from '@mui/material/Autocomplete';
import TextField from '@mui/material/TextField';
import { countries } from './data';

import useForm from '../@core/hooks/useForm';

import toast from '../@core/services/toast';

const MyForm = () => {
    const {
        state: [data, setProp],
        errors,
        inputProps,
        formProps,
        checkProps,
        autocompleteProps,
    } = useForm({
        initialValues: {
            name: '',
            email: '',
            acceptTerms: false,
            country: '',
        },
        validate: (data) => {
            const errors = [];

            if (!data.name) {
                errors.push({ key: 'name', message: 'Name is required.' });
            }

            if (!data.email) {
                errors.push({ key: 'email', message: 'Email is required.' });
            }

            if (!data.acceptTerms) {
                errors.push({ key: 'acceptTerms', message: 'You must accept the terms.' });
            }

            if (!data.country) {
                errors.push({ key: 'country', message: 'Country is required.' });
            }

            return errors;
        },
        onSubmit: (data) => {
            // Here you could do something before the request
            // and return false to cancel request
            console.log(data);
        },
        action: 'https://httpbin.org/post',
        method: 'post',
        onSuccess: (response) => {
            // do something with response
            toast.success('Form submitted successfully!');
        },
        onError: (error) => {
            // do something with error
            toast.error('An error occurred! ' + error.message);
        },
    });

    return (
        <form {...formProps()}>
            <TextField {...inputProps('name')} label="Name" />
            <TextField {...inputProps('email')} label="Email" />
            <Checkbox {...checkProps('acceptTerms')} />
            <Autocomplete 
                {...autocompleteProps('country')} 
                options={countries}
            />
            <Button type="submit" variant="contained" color="primary">
                Submit
            </Button>
        </form>
    );
};

export default MyForm;
```

Este exemplo mostra como usar o hook `useForm` com os componentes TextField, Checkbox, Autocomplete e Button do Material UI. O objeto `initialValues` define os valores iniciais do formulário, e a função `validate` é usada para verificar se todos os campos foram preenchidos corretamente antes de submeter o formulário. 

As funções `inputProps`, `checkProps` e `autocompleteProps` são usadas para gerar as props dos componentes TextField, Checkbox e Autocomplete, respectivamente. Essas props incluem o valor atual do campo, a função para atualizar este valor e a mensagem de erro, se houver. 

Por fim, a função `formProps` é usada para gerar as props do elemento `form`, que incluem a função para tratar o evento de submissão do formulário. Quando o formulário é submetido, a função `onSubmit` é chamada, e os dados do formulário são enviados para o console.