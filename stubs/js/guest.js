import { app } from '@arandu/laravel-mui-admin';

import createApp from './src/createApp';
import config from './src/config';

app.init(config).then(() => {
    createApp('guest');
});