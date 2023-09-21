import { runCoreMacros } from '@arandu/laravel-mui-admin';
import tables from './tables';

// register system core macros
runCoreMacros();

// register custom macros
tables();

