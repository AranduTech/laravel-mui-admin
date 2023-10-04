# Configurando o Ambiente

Neste guia, você aprenderá a configurar o ambiente de desenvolvimento para trabalhar com o Laravel MUI Admin. Abordaremos a instalação das dependências necessárias e a configuração básica do projeto.

## Pré-requisitos

Antes de começar, certifique-se de que você possui os seguintes requisitos instalados em seu sistema:

- PHP 7.4+
- Composer
- Node.js 14.0+
- Npm

## Passo a passo

1. **Clone o repositório**: faça uma cópia do repositório do Laravel MUI Admin em sua máquina local utilizando o comando `git clone`.

2. **Instale as dependências do PHP**: navegue até a pasta do projeto e execute o comando `composer install` para instalar todas as dependências do PHP.

3. **Instale as dependências do Node.js**: na mesma pasta do projeto, execute o comando `npm install` para instalar todas as dependências do Node.js.

4. **Configure o arquivo .env**: copie o arquivo `.env.example` para um novo arquivo chamado `.env` e configure as informações do banco de dados de acordo com seu ambiente local.

5. **Crie a chave do aplicativo**: execute o comando `php artisan key:generate` para gerar uma chave de aplicativo única e segura.

6. **Execute as migrations**: rode o comando `php artisan migrate --seed` para criar as tabelas no banco de dados e preenchê-las com dados iniciais. Anote a senha do usuário criado para uso posterior.

7. **Construa o frontend**: use o comando `npm run dev` para compilar os arquivos do frontend. Se você deseja um ambiente de desenvolvimento contínuo com hot reloading, execute `npm run hot`.

8. **Inicie o servidor**: execute o comando `php artisan serve` para iniciar o servidor de desenvolvimento local.

Agora você deve ter um ambiente de desenvolvimento funcional para o Laravel MUI Admin. Você pode começar a explorar o projeto e personalizá-lo de acordo com suas necessidades.

## Próximos passos

Depois de configurar o ambiente, você pode seguir para os outros guias disponíveis:

- [Customizando o tema](./customizando-o-tema.md)
- [Criando uma renderização](./criando-uma-renderizacao.md)
- [Criando Componentes](./criando-componentes.md)
- [Criando Páginas e Rotas](./criando-paginas-e-rotas.md)

Esses guias ajudarão você a entender melhor como trabalhar com o Laravel MUI Admin e a criar aplicativos web eficientes e personalizados.