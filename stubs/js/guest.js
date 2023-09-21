import { bootstrapApp } from '@arandu/laravel-mui-admin';

import createApp from './src/app';
import config from './src/config';

bootstrapApp(config);
createApp('guest');
