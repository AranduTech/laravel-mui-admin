import React from 'react';

import { ThemeProvider, CssBaseline, createTheme } from '@mui/material';

import { config, ToastProvider, DialogProvider } from '@arandu/laravel-mui-admin';
import { RouterProvider } from 'react-router-dom';


const App = ({ router, theme }) => {

    // This is the main component of your application.
    // Here you can add providers from packages like Redux, React Query, etc.
    // You can also add your own providers here.

    // In order to be able to use the `toast` and `dialog` functions
    // from '@arandu/laravel-mui-admin' you must have the `ToastProvider` and
    // `DialogProvider` in your component tree.

    return (
        <React.StrictMode>
            <ThemeProvider theme={theme}>
                <CssBaseline />
                <RouterProvider router={router} />
                <ToastProvider />
                <DialogProvider />
            </ThemeProvider>
        </React.StrictMode>
    );
};

export default App;