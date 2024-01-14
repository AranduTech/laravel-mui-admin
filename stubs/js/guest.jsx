import React from 'react';
import ReactDOM from 'react-dom';

import { app } from '@arandu/laravel-mui-admin';

import App from './components/App';
import config from './config';
import routes from './routes/guest';

import '../sass/app.scss';

window.addEventListener('load', async () => {
    const { router, theme } = await app.withRoutes(routes)
        .withConfig(config)
        .init();

    ReactDOM.render(
        <App
            router={router}
            theme={theme}
        />,
        document.getElementById('root'),
    );
});
