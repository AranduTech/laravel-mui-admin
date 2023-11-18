import React from 'react';

import Alert from '@mui/material/Alert';
import Button from '@mui/material/Button';
import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import CardContent from '@mui/material/CardContent';
import CardMedia from '@mui/material/CardMedia';
import Checkbox from '@mui/material/Checkbox';
import FormControlLabel from '@mui/material/FormControlLabel';
import Grid from '@mui/material/Unstable_Grid2';
import TextField from '@mui/material/TextField';
import Stack from '@mui/material/Stack';
import Typography from '@mui/material/Typography';
import useMediaQuery from '@mui/material/useMediaQuery';

import { Link } from 'react-router-dom';

import { useTranslation } from 'react-i18next';

import CsrfToken from '@arandu/laravel-mui-admin/lib/components/CsrfToken';
import { error, route, useClearErrorsOnExit } from '@arandu/laravel-mui-admin';

import useWindowHeight from '../../hooks/useWindowHeight';

export const Login = () => {
    useClearErrorsOnExit();
    const windowHeight = useWindowHeight();

    const isMd = useMediaQuery((theme) => theme.breakpoints.up('md'));

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
                    action={route('login')}
                >
                    <CardMedia
                        sx={{ height: 250 }}
                        image="/images/logo.webp"
                    />
                    <CardContent>
                        <Stack spacing={2}>
                            <Typography
                                gutterBottom
                                variant="h5"
                                component="div"
                            >
                                {t('welcome')}
                            </Typography>
                            <TextField
                                fullWidth
                                label={t('user.email')}
                                name="email"
                                type="email"
                                autoComplete="email"
                                error={!!error('email')}
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
                                autoComplete="current-password"
                                error={!!error('password')}
                            />
                            {error('password') && (
                                <Alert
                                    severity="error"
                                >
                                    {error('password')}
                                </Alert>
                            )}
                            <FormControlLabel
                                label={t('auth.remember')}
                                control={
                                    <Checkbox
                                        name="remember"
                                        type="checkbox"
                                    />
                                }
                            />

                            <CsrfToken />

                        </Stack>
                    </CardContent>
                    <CardActions
                        sx={{
                            display: 'flex',
                            justifyContent: 'space-between',
                            alignItems: 'end',
                        }}
                    >
                        <Stack
                            direction={isMd ? 'row' : 'column'}
                            spacing={1}
                            alignItems="flex-start"
                        >
                            {route.exists('password.request') && (
                                <Button
                                    component={Link}
                                    size="small"
                                    to={route('password.request')}
                                >
                                    {t('auth.password.forgot')}
                                </Button>
                            )}
                            {/* {route.exists('register') && (
                                <Button
                                    component={Link}
                                    size="small"
                                    to={route('register')}
                                >
                                    {t('auth.register')}
                                </Button>
                            )} */}
                        </Stack>
                        <Button
                            type="submit"
                            variant="contained"
                            size="small"
                        >
                            {t('auth.login')}
                        </Button>
                    </CardActions>
                </Card>
            </Grid>
        </Grid>

    );
};

export default Login;

