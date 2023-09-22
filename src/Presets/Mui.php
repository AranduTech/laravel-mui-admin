<?php

namespace Arandu\LaravelMuiAdmin\Presets;

use Laravel\Ui\Presets\Preset;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class Mui extends Preset
{
    /**
    * Install the preset.
    *
    * @return void
    */
    public static function install()
    {
        // static::ensureComponentDirectoryExists();
        static::updatePackages(false);
        static::updatePackages();
        static::updateWebpackConfiguration();
        static::updateBootstrapping();
        static::updateComponent();
        static::removeNodeModules();
    }

    /**
     * Update the given package array.
     *
     * @param  array  $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages, $configurationKey)
    {
        if ($configurationKey == 'devDependencies') {
            return Arr::except($packages, ['axios']);
        }
        return [
            '@arandu/laravel-mui-admin' => '^0.0.7',
            '@babel/preset-react' => '^7.13.13',
            '@emotion/react' => '^11.10.5',
            '@emotion/styled' => '^11.10.5',
            '@fontsource/roboto' => '^4.5.8',
            '@mui/icons-material' => '^5.11.0',
            '@mui/material' => '^5.13.4',
            '@reduxjs/toolkit' => '^1.9.1',
            'deep-object-diff' => '^1.1.9',
            'i18next' => '^22.4.14',
            'i18next-browser-languagedetector' => '^7.0.1',
            'normalize.css' => '^8.0.1',
            'react' => '^17.0.2',
            'react-dom' => '^17.0.2',
            'react-i18next' => '^12.3.1',
            'react-redux' => '^8.0.5',
            'redux-logger' => '^3.0.6',
            'axios' => '^1.4.0',
            'react-router-dom' => '^6.15.0',
            'uuid' => '^9.0.0',
        ] + Arr::except($packages, ['vue', 'vue-template-compiler']);
    }

    /**
     * Update the Webpack configuration.
     *
     * @return void
     */
    protected static function updateWebpackConfiguration()
    {
        copy(__DIR__.'/../../stubs/webpack.mix.js', base_path('webpack.mix.js'));
    }

    /**
     * Update the example component.
     *
     * @return void
     */
    protected static function updateComponent()
    {
        (new Filesystem())->delete(
            resource_path('js/components/ExampleComponent.vue')
        );

        (new Filesystem())->copyDirectory(
            __DIR__.'/../../stubs/css',
            resource_path('css')
        );

        (new Filesystem())->copyDirectory(
            __DIR__.'/../../stubs/js',
            resource_path('js')
        );

        (new Filesystem())->copyDirectory(
            __DIR__.'/../../stubs/lang',
            resource_path('lang')
        );

        (new Filesystem())->copyDirectory(
            __DIR__.'/../../stubs/sass',
            resource_path('sass')
        );

        (new Filesystem())->copyDirectory(
            __DIR__.'/../../stubs/views',
            resource_path('views')
        );

        (new Filesystem())->copyDirectory(
            __DIR__.'/../../seeders',
           'database/seeders'
        );

        (new Filesystem())->copyDirectory(
            __DIR__.'/../../src/Http/Controllers/Auth',
           'app/Http/Controllers/Auth'
        );

        (new Filesystem())->copy(
            __DIR__.'/../../src/Http/Controllers/RendererController.php',
           'app/Http/Controllers/RendererController.php'
        );
        
        (new Filesystem())->copy(
           __DIR__.'/../../src/Http/Controllers/RepositoryController.php',
           'app/Http/Controllers/RepositoryController.php'
        );

        (new Filesystem())->copyDirectory(
            __DIR__.'/../../src/Commands',
           'app/Console/Commands'
        );
    }

    /**
     * Update the bootstrapping files.
     *
     * @return void
     */
    protected static function updateBootstrapping()
    {
        copy(__DIR__.'/../../stubs/app.js', resource_path('js/app.js'));
    }
}
