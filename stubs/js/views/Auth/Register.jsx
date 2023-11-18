import React from 'react';

import Alert from '@mui/material/Alert';
import Button from '@mui/material/Button';
import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import CardContent from '@mui/material/CardContent';
import Grid from '@mui/material/Unstable_Grid2';
import TextField from '@mui/material/TextField';
import Stack from '@mui/material/Stack';
import Typography from '@mui/material/Typography';

import { Link } from 'react-router-dom';

import { useTranslation } from 'react-i18next';

import {
    config, route, error, useClearErrorsOnExit,
    CsrfToken,
} from '@arandu/laravel-mui-admin';

import useWindowHeight from '../../hooks/useWindowHeight';

export const Login = () => {
    useClearErrorsOnExit();

    const windowHeight = useWindowHeight();

    const { t } = useTranslation();

    return (
        <Grid
            spacing={1}
            disableEqualOverflow
            container
            justifyContent="center"
            alignItems="center"
            height={windowHeight}
        >
            <Grid
                xs={11}
                sm={8}
                md={6}
                lg={4}
                xl={3}
            >
                <Card
                    component="form"
                    method="POST"
                    action={route('register')}
                >
                    <CardContent>
                        <Typography
                            gutterBottom
                            variant="h5"
                            component="div"
                        >
                            {t('auth.register')}
                        </Typography>
                        <Stack spacing={2}>
                            <TextField
                                fullWidth
                                label={t('user.name')}
                                name="name"
                                type="name"
                                autoComplete="name"
                                defaultValue={config('boot.data.old.name') || ''}
                                error={!!error('name')}
                                required
                            />
                            {error('name') && (
                                <Alert
                                    severity="error"
                                >
                                    {error('name')}
                                </Alert>
                            )}
                            <TextField
                                fullWidth
                                label={t('user.email')}
                                name="email"
                                type="email"
                                autoComplete="email"
                                defaultValue={config('boot.data.old.email') || ''}
                                error={!!error('email')}
                                required
                            />
                            {error('email') && (
                                <Alert
                                    severity="error"
                                >
                                    {error('email')}
                                </Alert>
                            )}
                            <TextField
                                fullWidth
                                label={t('user.password')}
                                name="password"
                                type="password"
                                autoComplete="new-password"
                                error={!!error('password')}
                                required
                            />
                            {error('password') && (
                                <Alert
                                    severity="error"
                                >
                                    {error('password')}
                                </Alert>
                            )}
                            <TextField
                                fullWidth
                                label={t('auth.password.confirm')}
                                name="password_confirmation"
                                type="password"
                                autoComplete="new-password"
                                required
                            />
                            <CsrfToken />
                        </Stack>
                    </CardContent>
                    <CardActions
                        sx={{
                            display: 'flex',
                            justifyContent: 'space-between',
                        }}
                    >
                        <Button
                            component={Link}
                            size="small"
                            to={-1}
                        >
                            {t('navigate.back')}
                        </Button>
                        <Button
                            type="submit"
                            variant="contained"
                            size="small"
                        >
                            {t('navigate.submit')}
                        </Button>
                    </CardActions>
                </Card>
            </Grid>
        </Grid>

    );
};

export default Login;

