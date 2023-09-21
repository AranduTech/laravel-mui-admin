import React from 'react';
import ReactDOM from 'react-dom';

import { RouterProvider } from 'react-router-dom';
import { Provider as ReduxProvider } from 'react-redux';
import { CssBaseline, ThemeProvider, createTheme } from '@mui/material';

import createRouter from './routes';
import state from './state';
import ToastProvider from '@arandu/laravel-mui-admin/lib/components/ToastProvider';
import DialogProvider from '@arandu/laravel-mui-admin/lib/components/DialogProvider';

import { config } from '@arandu/laravel-mui-admin';

export default {

    admin: (rootElement) => {
        createRouter('admin').then((router) => {
            ReactDOM.render(
                <React.StrictMode>
                    <ReduxProvider store={state.store}>
                        <ThemeProvider theme={createTheme(config('theme'))}>
                            <CssBaseline />
                            <RouterProvider router={router} />
                            <ToastProvider />
                            <DialogProvider />
                        </ThemeProvider>
                    </ReduxProvider>
                </React.StrictMode>,
                rootElement,
            );
        });
    },

    authenticated: (rootElement) => {
        createRouter('authenticated').then((router) => {
            ReactDOM.render(
                <React.StrictMode>
                    <ReduxProvider store={state.store}>
                        <ThemeProvider theme={createTheme(config('theme'))}>
                            <CssBaseline />
                            <RouterProvider router={router} />
                            <ToastProvider />
                            <DialogProvider />
                        </ThemeProvider>
                    </ReduxProvider>
                </React.StrictMode>,
                rootElement,
            );
        });
    },

    guest: (rootElement) => {
        createRouter('guest').then((router) => {
            ReactDOM.render(
                <React.StrictMode>
                    <ThemeProvider theme={createTheme(config('theme'))}>
                        <CssBaseline />
                        <RouterProvider router={router} />
                        <ToastProvider />
                        <DialogProvider />
                    </ThemeProvider>
                </React.StrictMode>,
                rootElement,
            );
        });
    },

};
