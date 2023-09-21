<?php

return [

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


];
