## Introdução

O Laravel MUI Admin é um boilerplate que visa facilitar a construção de aplicações web utilizando Laravel no backend e React no frontend. Este projeto oferece uma estrutura sólida e diversas ferramentas que simplificam o processo de desenvolvimento, permitindo que você se concentre na lógica de negócios da sua aplicação.

### Utilização

#### Backend

O backend do Laravel MUI Admin é baseado na estrutura padrão do Laravel, com algumas funções e configurações adicionais para aprimorar o desenvolvimento:

- `app/Services/React.php`: serviço responsável por manipular dados pré-carregados no frontend. É utilizado principalmente para interagir com endpoints nativos de autenticação e injetar o usuário autenticado no frontend.

#### Frontend

O frontend é construído com React e utiliza o framework Material UI para componentes e o Redux para gerenciamento de estado global.

##### Estrutura

A organização das pastas do frontend é projetada para replicar a arquitetura do Laravel, com algumas modificações para melhorar a organização e facilitar o desenvolvimento:

- `resources/js`: pasta raiz do frontend
- `resources/js/src/components`: componentes React
- `resources/js/src/lang`: arquivos de traduções. Consulte a documentação do `react-i18next` para mais informações
- `resources/js/src/routes`: arquivos de rotas. Consulte a documentação do `react-router-dom` para mais informações
- `resources/js/src/views`: páginas React
- `resources/js/src/api`: agrupamento dos serviços de acesso ao backend e funcionalidades do frontend
  - `resources/js/src/api/@core`: classes e funções base para os serviços
  - `resources/js/src/api/config`: configurações do frontend, análogas à pasta `config` do Laravel
  - `resources/js/src/api/constants`: constantes utilizadas no frontend
  - `resources/js/src/api/models`: modelos de dados utilizados no frontend, espelhando os modelos do backend
  - `resources/js/src/api/state`: espaço para criação de estados globais do Redux

Com esta estrutura pré-definida e as ferramentas integradas, o Laravel MUI Admin facilita o desenvolvimento de aplicações web robustas e eficientes, permitindo que você se concentre em entregar valor aos usuários finais.