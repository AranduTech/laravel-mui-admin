# Customizando o tema

Neste guia, você aprenderá como customizar o tema do projeto Laravel MUI Admin, incluindo as cores, tipografia e outros elementos visuais. A personalização do tema é realizada através do arquivo `resources/js/src/api/config/theme.js`.

## Modificando o tema existente

Para alterar o tema do projeto, siga os passos abaixo:

1. Abra o arquivo `resources/js/src/api/config/theme.js` em seu editor de código preferido. Este arquivo contém um objeto que pode ser usado para sobrescrever as configurações padrão do Material UI.

2. Para customizar o tema, descomente a seção `palette` e modifique as propriedades de cores, como por exemplo, a cor primária:

```javascript
export default {
    palette: {
        primary: {
            main: '#sua_cor_primaria',
            light: '#sua_cor_primaria_clara',
            dark: '#sua_cor_primaria_escura',
        },
    },
};
```

3. Salve o arquivo e reinicie o servidor de desenvolvimento, se necessário. As alterações no tema serão aplicadas automaticamente.

## Aplicando o tema personalizado

1. Abra o arquivo `resources/js/src/renderer.jsx`, que é a raiz da aplicação.

2. Localize a linha que cria a instância do tema:

```javascript
const theme = createTheme(api.config.theme);
```

Essa linha importa as configurações do tema do arquivo `theme.js` e cria um tema com essas configurações usando a função `createTheme`.

3. O tema criado é aplicado usando o componente `<ThemeProvider>` do Material UI:

```jsx
<ThemeProvider theme={theme}>
    {/* ...resto da aplicação */}
</ThemeProvider>
```

Agora, o tema personalizado será aplicado em toda a aplicação.

Para obter informações mais detalhadas sobre como customizar o tema, consulte a [documentação do Material UI](https://mui.com/material-ui/customization/theming/).

Com essas etapas, você poderá customizar o tema do projeto Laravel MUI Admin. Aproveite a flexibilidade oferecida pelo Material UI para criar uma experiência visual única e envolvente para os usuários do seu aplicativo.

