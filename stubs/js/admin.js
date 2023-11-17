import { app } from '@arandu/laravel-mui-admin';

import createApp from './src/app';
import config from './src/config';

app.init(config).then(() => {
    createApp('admin');
});

