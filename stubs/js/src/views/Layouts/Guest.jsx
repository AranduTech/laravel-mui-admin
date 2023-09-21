import React from 'react';

import { useTranslation } from 'react-i18next';
import { Outlet } from 'react-router-dom';

import Button from '@mui/material/Button';
import Toolbar from '@mui/material/Toolbar';
import AppBar from '@mui/material/AppBar';
import Stack from '@mui/material/Stack';
import Typography from '@mui/material/Typography';

import Link from '@arandu/laravel-mui-admin/lib/components/Link';
import { route } from '@arandu/laravel-mui-admin';

/**
 * Componente raiz de navegação e layout para a área de visitantes.
 *
 * @component
 */
const Guest = () => {
    const { t } = useTranslation();
    return (
        <Stack spacing={0}>
            <AppBar color="transparent">
                <Toolbar>
                    <Typography
                        width="100%"
                        variant="h6"
                        color="primary.main"
                        noWrap
                        component={Link}
                        to={route('home')}
                    >
                        {document.title}
                    </Typography>

                    {route.exists('login') && (
                        <Button
                            variant="text"
                            color="primary"
                            component={Link}
                            noWrap
                            to={route('login')}
                        >
                            {t('auth.login')}
                        </Button>
                    )}

                    {route.exists('register') && (
                        <Button
                            variant="text"
                            color="primary"
                            component={Link}
                            noWrap
                            to={route('register')}
                        >
                            {t('auth.register')}
                        </Button>
                    )}
                </Toolbar>
            </AppBar>
            <Outlet />
        </Stack>
    );
};

export default Guest;
