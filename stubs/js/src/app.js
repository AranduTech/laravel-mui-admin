/* eslint-disable no-undef */
import renderer from './renderer';

import { app } from '@arandu/laravel-mui-admin';

export default (rendererName) => {

    if (app.getDefinition('data.user')) {
        require('./models');
        require('./macros');
    }

    if (!Object.keys(renderer).includes(rendererName)) {
        throw new Error(`Renderer ${rendererName} is not defined.`);
    }

    /**
     * Next, we will create a fresh React component instance and attach it to
     * the page. Then, you may begin adding components to this application
     * or customize the JavaScript scaffolding to fit your unique needs.
     */
    const rootElement = document.getElementById('root');

    if (rootElement) {
        // Render the app.
        renderer[rendererName](rootElement);
    }
};
