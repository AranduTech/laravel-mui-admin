import React from 'react';

import Alert from '@mui/material/Alert';
import Button from '@mui/material/Button';
import Card from '@mui/material/Card';
import CardContent from '@mui/material/CardContent';
import CardActions from '@mui/material/CardActions';
import Grid from '@mui/material/Unstable_Grid2';
import Stack from '@mui/material/Stack';
import TextField from '@mui/material/TextField';
import Typography from '@mui/material/Typography';

import { Link, useParams, useSearchParams } from 'react-router-dom';

import { useTranslation } from 'react-i18next';

import CsrfToken from '@arandu/laravel-mui-admin/lib/components/CsrfToken';

import { error, route, useClearErrorsOnExit } from '@arandu/laravel-mui-admin';

import useWindowHeight from '../../../hooks/useWindowHeight';

const Reset = () => {
    useClearErrorsOnExit();

    const windowHeight = useWindowHeight();

    const { token } = useParams();

    const [search] = useSearchParams();

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
                    action={route('password.update')}
                >
                    <CsrfToken />

                    <CardContent>
                        <Typography
                            gutterBottom
                            variant="h5"
                            component="div"
                        >
                            {t('auth.password.reset')}
                        </Typography>
                        <Stack spacing={2}>
                            <input
                                type="hidden"
                                name="token"
                                value={token}
                            />
                            <TextField
                                fullWidth
                                label={t('user.email')}
                                name="email"
                                type="email"
                                autoComplete="email"
                                defaultValue={search.get('email') || ''}
                                error={!!error('email')}
                                required
                            />
                            {error('email') && (
                                <Alert severity="error">
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
                                <Alert severity="error">
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
                            to={route('login')}
                            size="small"
                        >
                            {t('auth.login')}
                        </Button>
                        <Button
                            type="submit"
                            variant="contained"
                            size="small"
                        >
                            {t('auth.password.reset')}
                        </Button>
                    </CardActions>
                </Card>
            </Grid>
        </Grid>

    );
};

export default Reset;

