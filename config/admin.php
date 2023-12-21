<?php

return [

    /**
     * --------------------------------------------------------------------------
     * Models & Routes Manifest
     * --------------------------------------------------------------------------
     * 
     * This value configures how the SPA will receive data about the models
     * and routes available in the application. This is used to generate the
     * admin panel.
     * 
     * Possible values are 'api' and 'bundle'. If you choose 'api', the manifest
     * data will be appended to the initialization API response. If you choose
     * 'bundle', the manifest data will be genereated into 
     * `resources/js/src/config/boot.json`, and you should include this file
     * in your main configuration file.
     * 
     * If you choose 'bundle', you should run `php artisan admin:manifest` after
     * any change in the models or routes and during deployment.
     *
     */
    'manifest' => env('ADMIN_MANIFEST', 'api'),

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    |
    | This value is the list of the core roles used by the application. These
    | configurations affects the admin panel and the user registration.
    |
    */
    'roles' => [

        /*
        |--------------------------------------------------------------------------
        | Admin Role
        |--------------------------------------------------------------------------
        |
        | This value is the name of the role that will be used as the admin role.
        | When seeding the roles, or creating the admin user via the command line,
        | this is the role name that will be used. This role will have all the
        | abilities enabled by default, and will be the only role that can access
        | the admin panel.
        | If you want to rename the role for some reason, you should update this
        | value to match the new name.
        |
        */
        'admin' => env('ADMIN_ROLE', 'admin'),

        /*
        |--------------------------------------------------------------------------
        | Subscriber Role
        |--------------------------------------------------------------------------
        |
        | This value is the name of the role that will be used as the subscriber
        | role. When seeding the roles, or creating users via "register" feature,
        | this is the role name that will be used. This role will have no abilities
        | enabled by default.
        | If you want to rename the role for some reason, you should update this
        | value to match the new name.
        */
        'subscriber' => env('SUBSCRIBER_ROLE', 'subscriber'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | The following configuration options are used to control the cache of the
    | admin panel. The admin panel uses the cache to store the list of models
    | that are available to be managed by the admin panel. This is done to
    | improve the performance of the admin panel.
    |
    */
    'cache' => [
            
        /*
        |--------------------------------------------------------------------------
        | Cache Key Namespace
        |--------------------------------------------------------------------------
        |
        | This value is the namespace that will be used to store the cache. 
        | Which means that the cache will be stored in a key that starts with
        | this value. This value should be unique, and should not be used by
        | any other cache key. Change this value if other packages are
        | conflicting with this one.
        |
        | Setting to a falsy value will disable all cache features.
        |
        | If you change this during the application lifetime, you should clear
        | the cache manually.
        |
        */
        'key' => env('ADMIN_CACHE_KEY', 'admin.cache'),

        /*
        |--------------------------------------------------------------------------
        | Cache Duration
        |--------------------------------------------------------------------------
        |
        | This value is the duration that the cache will be stored. This value
        | should be a number of minutes. The default value is 60 minutes.
        |
        */
        'ttl' => (int)env('ADMIN_CACHE_DURATION', 60),

    ],


    /*
    |--------------------------------------------------------------------------
    | CMS Configuration
    |--------------------------------------------------------------------------
    |
    | The following configuration options are used to control the CMS of the
    | admin panel. The CMS is used to manage the content of the application.
    |
    */
    'cms' => [

        /*
        |--------------------------------------------------------------------------
        | API Controller overrides
        |--------------------------------------------------------------------------
        |
        | This value is the list of the controller overrides used by the CMS.
        | If you need to create a custom controller for a model, you can add
        | the model class name as the key, and the controller class name as
        | the value. The controller class should extend the 
        | 'Arandu\LaravelMuiAdmin\Http\Controllers\RepositoryController' class
        | for ease of use.
        |
        */
        'controller_overrides' => [
            // 'App\Models\Example' => 'App\Http\Controllers\ExampleController',
        ],

        /*
        |--------------------------------------------------------------------------
        | Tracking Model Changes
        |--------------------------------------------------------------------------
        |
        | This is the model used as User model. This model will be used to track
        | the changes made to the models. For example, when a model is created,
        | the user that created the model will be stored in the 'created_by'
        | column. This model will be used to retrieve the user that created
        | or updated the model.
        |
        */
        'user_model' => env('ADMIN_CMS_USER_MODEL', 'App\Models\User'),
    ],

    'api' => [

        /*
        |--------------------------------------------------------------------------
        | API Prefix
        |--------------------------------------------------------------------------
        |
        | This value is the prefix that will be used in the API routes. You
        | should change this if other routes are conflicting with `Laravel MUI
        | Admin` routes.
        |
        */
        'prefix' => env('ADMIN_API_PREFIX', 'admin'),

        /*
        |--------------------------------------------------------------------------
        | API Middleware
        |--------------------------------------------------------------------------
        |
        | This value is the list of the middleware that will be used in the API
        | routes.
        |
        */
        'middleware' => [
            'auth',
            'verified',
        ],


    ],

    'bi' => [

        'dashboards_namespace' => 'Dashboards',

        'api' => [

            /*
            |--------------------------------------------------------------------------
            | BI API Prefix
            |--------------------------------------------------------------------------
            |
            | This value is the prefix that will be used in the BI API routes. You
            | should change this if other routes are conflicting with `Laravel MUI
            | Admin` routes.
            |
            */
            'prefix' => env('ADMIN_BI_API_PREFIX', 'admin/bi'),

            /*
            |--------------------------------------------------------------------------
            | BI API Middleware
            |--------------------------------------------------------------------------
            |
            | This value is the list of the middleware that will be used in the BI
            | API routes.
            */
            'middleware' => [
                'auth',
                'role:' . env('ADMIN_ROLE', 'admin'),
            ],
        ]

    ],
];
