const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

const packageJson = require('./package.json');
const dependencies = Object.keys({
    ...packageJson.devDependencies,
    ...packageJson.dependencies,
});

mix.js('resources/js/guest.js', 'public/js')
    .js('resources/js/authenticated.js', 'public/js')
    .js('resources/js/admin.js', 'public/js')
    .extract(dependencies)
    .sourceMaps(false)
    .react()
    .sass('resources/sass/app.scss', 'public/css');
