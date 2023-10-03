import { bootstrapApp, app } from '@arandu/laravel-mui-admin';

import createApp from './src/app';
import config from './src/config';

bootstrapApp(config);

app.init().then(() => {
    createApp('guest');
});