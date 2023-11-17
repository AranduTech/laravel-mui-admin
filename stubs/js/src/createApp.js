/* eslint-disable no-undef */
import React from 'react';

import createRouter from './routes';
import renderer from './renderer';

import App from './components/App';

export default async (rendererName) => {

    await import('./macros');


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
        // Create the router.
        createRouter(rendererName).then((router) => {
            // Render the app.
            ReactDOM.render(
                <App router={router} />,
                rootElement,
            );
        });
    }
};
