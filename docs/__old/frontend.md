
### Os modelos eloquentes do frontend

Depois que um modelo foi adicionado ao painel administrativo, um modelo frontend pode ser recuperado para essa classe. Este modelo frontend será usado para renderizar a página do modelo, criar e editar formulários e para manipular os dados do modelo. Para recuperar o modelo frontend, use o método `ModelRepository.getModelClass`:

```js
import { modelRepository } from '@arandu/laravel-mui-admin';

const Post = modelRepository.getModelClass('post'); // o parâmetro deve estar em snake case (ex: 'blog_post' para o modelo BlogPost)

// use a classe Post para criar um novo modelo
const post = new Post({
    title: 'Título do Post',
    content: 'Conteúdo do Post',
});

// você pode definir os atributos fluentemente
post.title = 'Novo título';

// salve o modelo
post.save().then(() => {
    // faça algo após salvar
});
```

Para buscar uma lista de modelos, você pode usar o `axios` juntamente com a função `route`:

```js
import axios from 'axios';

const postsResponse = await axios.get(route('admin.post.list'), {
    params: {
        page: 1,
        per_page: 10,
        // use este objeto para adicionar filtros
    },
});

// isso retornará uma lista de modelos Post
const posts = postsResponse.data.data.map((post) => new Post(post));
```

Alternativamente, se você estiver em um componente funcional, pode usar o hook `useFetchList`. Isso mapeará automaticamente a resposta para uma lista de modelos:

```jsx
import { useFetchList, modelRepository } from '@arandu/laravel-mui-admin';

const Post = modelRepository.getModelClass('post');

const Posts = () => {
    const { items: posts, request } = useFetchList(Post);

    const { loading, error } = request;

    if (loading) {
        return <div>Carregando...</div>;
    }

    if (error) {
        return <div>Erro: {error.message}</div>;
    }

    return (
        <div>
            {posts.map((post) => (
                <div key={post.id}>{post.title}</div>
            ))}
        </div>
    );
};
```

Este hook reflete e gerencia os parâmetros de pesquisa na URL. Por exemplo, se a URL for `/posts?q=foo`, o hook adicionará automaticamente o parâmetro `q` à solicitação.

Para obter uma documentação completa sobre os modelos de frontend, consulte a documentação do `@arandu/laravel-mui-admin`.


### Relacionamentos


Com a configuração correta das relações do modelo no backend, será possível disponibilizar para o frontend as informações das relações. Isso torna possível recuperar o modelo relacionado a partir do modelo de frontend quando o relacionamento está carregado. Por exemplo:

```js
const response = await axios.get(route('admin.post.item', { id: 1 }));
// a resposta.data será um modelo Post com o relacionamento user carregado
// {
//     id: 1,
//     title: 'Título do Post',
//     content: 'Conteúdo do Post',
//     user: {
//         id: 1,
//         name: 'John Doe',
//     },
// }

const post = new Post(response.data);

// o relacionamento user está carregado
const user = post.user;

// você pode trabalhar no modelo relacionado
user.name = 'Novo nome';
user.save();
```