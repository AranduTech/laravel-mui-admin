# Laravel MUI Admin

A highly customizable admin panel for Laravel 8.x, built with [Material-UI](https://material-ui.com/) and [React](https://reactjs.org/), inspired by Wordpress.

## Installation

 > **Note:** This package is still in development and is not ready for production use.

 > **Note:** This is a Documentation Driven Development project. The code is not ready yet.

 > If you are installing this package on a existing project, make sure you have a backup of your files.

Pre-requisites:
    
     - PHP 7.4
     - Laravel 8.x
     - Node 14.x or higher

Follow the installation steps for [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v5/introduction) and install the [Laravel UI](https://github.com/laravel/ui) package, skipping the scaffold generation step.

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
composer require laravel/ui
```
 > **Note:** There are steps not described here. Please follow the documentation of each package.

Then, require this package:

```bash
composer require arandu/laravel-mui-admin
```

Publish config and assets:

```bash
php artisan vendor:publish --provider="Arandu\\LaravelMuiAdmin\\AdminServiceProvider"
php artisan ui mui --auth
```

Install the node dependencies and build the assets:

```bash
npm install && npm run dev
```
## Configuration

Add the following lines of code to the `web.php` route file:

```php
use Arandu\LaravelMuiAdmin\Facades\Admin;
use Illuminate\Support\Facades\Auth;

Auth::routes();
Admin::web();
```

Add the following lines of code to the `api.php` route file:

```php
use Arandu\LaravelMuiAdmin\Facades\Admin;

Admin::api();
```

In the file `app/Providers/RouteServiceProvider.php`, change the const `HOME` to `'/'`. Example:

```php
    public const HOME = '/';
```

Verify that the `HasRoles` trait has been added to the `User.php` model.
Then add the `HasAdminSupport` trait.
Example:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Arandu\LaravelMuiAdmin\Traits\HasAdminSupport;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasAdminSupport;
    use HasRoles;

    // ...
}
```

# Basic Usage

 - Create the initial roles
 - Create a new admin user
 - Rendering the admin panel
 - Preparing a model for the admin panel
    - Adding the `HasAdminSupport` trait
    - The frontend eloquent models
    - Customization
        - Customize the columns of the model's page
        - Adding custom fields
        - Adding custom tabs
        - Adding custom search

## Create the initial roles

To create the initial roles, add the seeder `RolesAndPermissionsSeeder` to the `DatabaseSeeder` file. Example:

```php
    $this->call([
        // ...
        RolesAndPermissionsSeeder::class,
    ]);
```
Then, run the seeder classes using the following command:

```bash
php artisan db:seed
```

Alternatively, you can run the following command to seed only the roles and permissions:

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

## Create a new admin user

To create a new admin user, run the following command:

```bash
php artisan admin:credentials
```

This command will ask you for a username, email and password, and will create a new user with the `admin` role.

## Rendering the admin panel

If you have followed the installation steps and all assets are built, you should be able to access the admin after logging in. Use the credentials you created in the previous step.

However, if you need to manually render the admin panel, go to npm `@arandu/laravel-mui-admin` package documentation.

## Preparing a model for the admin panel

To prepare a model for the admin panel, you need to add the `HasAdminSupport` trait to it. This trait will add the necessary methods to the model to make it work with the admin panel. 

Also, you should have the `$fillable` property set on the model, so the admin panel can know which fields are available for the model.

### Adding the `HasAdminSupport` trait

```php
use Arandu\LaravelMuiAdmin\Traits\HasAdminSupport;

class Post extends Model
{
    use HasAdminSupport;

    protected $fillable = [
        'title',
        'content',
    ];
}
```

After adding the trait, there should be a route matching the model name in plural and snake case. For example, if the model is named `Post`, the route should be `/posts`. If the model is named `BlogPost`, the route should be `/blog_posts`. Add a link in the side menu by changing the `resources/js/src/views/Layouts/Admin.jsx` file:

```jsx
import { NavLink } from 'react-router-dom';
import { route } from '@arandu/laravel-mui-admin';

const navMenuItems = [
    // ...
    {
        key: 3,
        text: 'Posts',
        icon: 'posts',
        ListItemButtonProps: {
            component: NavLink,
            to: route('admin.post.index'),
        },
    },
]
```

Note that the `route` function is used to get a route path from its name. After adding `HasAdminSupport` trait to the model, the following routes are available:

 - WEB:
    - `admin.{model_name}.index`: The model's page
 - API:
    - `admin.{model_name}.list`: Get a paginated list of models
    - `admin.{model_name}.create`: Create a new model
    - `admin.{model_name}.item`: Get a model by id
    - `admin.{model_name}.update`: Update a model by id
    - `admin.{model_name}.delete`: Delete a model by id

If the model has `SoftDeletes` trait, the following routes are also available:

 - API:
    - `admin.{model_name}.restore`: Restore a model by id
    - `admin.{model_name}.forceDelete`: Force delete a model by id

If you want to customize the routes, you can do so by adding the following methods to the model:
    
```php
public function getWebUrls()
{
    return [
        'index' => 'custom/url/path',
        // use this array to create additional web routes if you wish
    ];
}

public function getApiUrls()
{
    return [
        'list' => 'custom/url/to/posts',
        'item' => 'custom/url/to/posts/{id}',
        'create' => [
            'url' => 'custom/url/to/posts/create',
            'method' => 'post',
        ],
        'update' => [
            'url' => 'custom/url/to/posts/{id}/update',
            'method' => 'post',
        ],
        'delete' => [
            'url' => 'custom/url/to/posts/{id}/delete',
            'method' => 'delete',
        ],
    // If the model has SoftDeletes trait add the following too:
    //    'restore' => [
    //        'url' => 'custom/url/to/posts/{id}/restore',
    //        'method' => 'post',
    //    ],
    //    'forceDelete' => [
    //        'url' => 'custom/url/to/posts/{id}/force-delete',
    //        'method' => 'delete',
    //    ],
    ];

    
}
```

### The frontend eloquent models

After a model has been added to the admin panel, a frontend model can be retrieved for that class. This frontend model will be used to render the model's page, create and edit forms, and to handle the model's data. To retrieve the frontend model, use the `ModelRepository.getModelClass` method:

```js
import { modelRepository } from '@arandu/laravel-mui-admin';

const Post = modelRepository.getModelClass('post'); // the parameter should be in snake case (ex: 'blog_post' for BlogPost model)

// use the Post class to create a new model
const post = new Post({
    title: 'Post title',
    content: 'Post content',
});

// you can set the attributes fluently
post.title = 'New title';

// save the model
post.save().then(() => {
    // do something after saving
});
```

To fetch a list of models, you can use `axios` alongside with the `route` function:

```js   
import axios from 'axios';

const postsResponse = await axios.get(route('admin.post.list'), {
    params: {
        page: 1,
        per_page: 10,
        // use this object to add filters
    },
});

// this will return a list of Post models
const posts = postsResponse.data.data.map((post) => new Post(post));
```

Alternatively, if you are in a functional component, you can use the `useFetchList` hook. This will automatically map the response to a list of models:

```jsx
import { useFetchList, modelRepository } from '@arandu/laravel-mui-admin';

const Post = modelRepository.getModelClass('post');

const Posts = () => {
    const { items: posts, request } = useFetchList(Post);

    const { loading, error } = request;

    if (loading) {
        return <div>Loading...</div>;
    }

    if (error) {
        return <div>Error: {error.message}</div>;
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

This hook reflects and handles the search params in the url. For example, if the url is `/posts?q=foo`, the hook will automatically add the `q` param to the request.

For complete documentation on the frontend models, check the `@arandu/laravel-mui-admin` documentation.

#### Note about relationships

Because of the frontend models, this package verifies every relationship that exists in the "backend" model. To make this work, the relationship methods should be defined in the model with type hints. For example:

```php
class Post extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

This makes it possible to retrieve the related model from the frontend model, when the relationship is loaded. For example:

```js
const response = await axios.get(route('admin.post.item', { id: 1 }));
// the response.data will be a Post model with the user relationship loaded
// {
//     id: 1,
//     title: 'Post title',
//     content: 'Post content',
//     user: {
//         id: 1,
//         name: 'John Doe',
//     },
// }

const post = new Post(response.data);

// the user relationship is loaded
const user = post.user;

// you can work on the related model
user.name = 'New name';
user.save();
```


### Customization

The `RepositoryIndex` component from the `@arandu/laravel-mui-admin` package is responsible for rendering the list of models, handling pagination, filters, actions, creating, editing and deleting models. There are several ways to customize the looks of this component. This documentation will cover what can be done at backend level to customize columns, forms, tabs and the search. For frontend customization, check the `RepositoryIndex` component documentation.

#### Customize the columns of the model's page

By default, the `RepositoryIndex` component will render a table with columns matching the `$fillable` property of the model. If you want to customize the columns, you should create a class in your project at `app/Admin/Tables/{$model}Table.php`. For example, if the model is named `Post`, the class should be named `PostTable`. This class should extend the `Arandu\LaravelMuiAdmin\Contracts\Table` class.

The created class should have at least a method called `default`, which will be used when no other method is specified. For example, if you want to customize the columns for the `Post` model, you should create a class at `app/Admin/Tables/PostTable.php` with the following contents:

```php
<?php

namespace App\Admin\Tables;

use Arandu\LaravelMuiAdmin\Contracts\Table;

class PostTable extends Table
{
    public function default()
    {
        return [
            [
                // The key is the name of the attribute on the model
                'key' => 'title',
                // The label is the text that will be displayed on the column header
                'label' => __('Title'),
            ],
            [
                // You can also use dot notation to access nested attributes
                'key' => 'author.name',
                'label' => __('Author Name'),
            ],
            [
                // You can create custom columns to be handled later on the frontend
                'key' => 'categories',
                'label' => __('Categories'),
            ]
        ];
    }
}
```

### Adding custom fields

By default, the `RepositoryIndex` component will render a form with fields matching the `$fillable` property of the model, and all fields will be of `text` type. If you want to customize the fields, you should create a class in your project at `app/Admin/Forms/{$model}Form.php`. For example, if the model is named `Post`, the class should be named `PostForm`. This class should extend the `Arandu\LaravelMuiAdmin\Contracts\Form` class and should have at least a method called `default`, which will be used when no other method is specified. For example, if you want to customize the fields for the `Post` model, you should create a class at `app/Admin/Forms/PostForm.php` with the following contents:

```php
<?php

namespace App\Admin\Forms;

use Arandu\LaravelMuiAdmin\Contracts\Form;

class PostForm extends Form
{
    public function default()
    {
        return [
            [
                // The key is the name of the attribute on the model
                'name' => 'title',
                // The label is the text that will be displayed on the field
                'label' => __('Title'),
                // The type is the type of the field
                // This could be any default HTML input type, or the types supported
                // by the @arandu/laravel-mui-admin package
                // if ommited, the type will be 'text'
                'type' => 'text',
                // any additional properties will be passed to the input component
                'required' => true,
            ],
            [
                'name' => 'content',
                'label' => __('Content'),
                'type' => 'textarea',
            ],
        ];
    }
}
```

### Adding custom tabs

The tabs that appear on the page should be customized in the frontend by registering a filter using the `addFilter` method of the `@arandu/laravel-mui-admin` package. Check the `RepositoryIndex` component documentation for more information. 

To handle the tab queries, override the `scopeTab` method on the model. For example, if you want to add a tab to show only the posts that are published, you should add the following method to the `Post` model:

```php
public function scopeTab($query, $tab)
{
    if ($tab === 'published') {
        return $query->where('published', true);
    }

    return $query;
}
```

By default the `RepositoryIndex` component will render a tab with the name `all` that will show all the models. If the model has the `SoftDeletes` trait, the `RepositoryIndex` component will also render a tab with the name `trashed` that will show only the deleted models. Make sure to handle these tabs in the `scopeTab` method.

### Adding search

To handle the search queries, override the `scopeSearch` method on the model. For example, if you want to add a search to find posts by title, you should add the following method to the `Post` model:

```php
public function scopeSearch($query, $search)
{
    return $query->where('title', 'like', "%{$search}%");
}
```

### Digging deeper

For more information about the resources in this package, full documentation is coming. For now, check the `@arandu/laravel-mui-admin` package documentation.
