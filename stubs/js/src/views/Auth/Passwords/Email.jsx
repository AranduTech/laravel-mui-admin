import React from 'react';

import Alert from '@mui/material/Alert';
import Button from '@mui/material/Button';
import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import CardContent from '@mui/material/CardContent';
import Grid from '@mui/material/Unstable_Grid2';
import Stack from '@mui/material/Stack';
import TextField from '@mui/material/TextField';
import Typography from '@mui/material/Typography';

import { Link } from 'react-router-dom';

import { useTranslation } from 'react-i18next';

import CsrfToken from '@arandu/laravel-mui-admin/lib/components/CsrfToken';
import {
    blade, route, error, useClearErrorsOnExit,
} from '@arandu/laravel-mui-admin';

import useWindowHeight from '../../../hooks/useWindowHeight';

const Email = () => {
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
                    action={route('password.email')}
                >
                    <CsrfToken />
                    <CardContent>
                        <Stack spacing={1}>
                            <Typography
                                gutterBottom
                                variant="h5"
                                component="div"
                            >
                                {t('auth.password.send')}
                            </Typography>
                            {blade('status') && (
                                <Alert severity="success">
                                    {blade('status')}
                                </Alert>
                            )}
                            <TextField
                                fullWidth
                                label={t('user.email')}
                                name="email"
                                type="email"
                                autoComplete="email"
                                defaultValue={blade('old.email') || ''}
                                required
                                error={!!error('email')}
                            />
                            {error('email') && (
                                <Alert severity="error">
                                    {error('email')}
                                </Alert>
                            )}
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
                            to={route('login')}
                        >
                            {t('navigate.back')}
                        </Button>
                        <Button
                            sx={{ display: 'inline' }}
                            size="small"
                            type="submit"
                            variant="contained"
                        >
                            {t('navigate.submit')}
                        </Button>
                    </CardActions>
                </Card>
            </Grid>
        </Grid>

    );
};

export default Email;
