import React from 'react';

import Alert from '@mui/material/Alert';
import Button from '@mui/material/Button';
import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import CardContent from '@mui/material/CardContent';
import Grid from '@mui/material/Unstable_Grid2';
import Typography from '@mui/material/Typography';
import CsrfToken from '@arandu/laravel-mui-admin/lib/components/CsrfToken';
import { useTranslation } from 'react-i18next';

import { blade, route } from '@arandu/laravel-mui-admin';

const Verify = () => {
    const { t } = useTranslation();

    return (
        <Grid
            spacing={1}
            disableEqualOverflow
            container
            justifyContent="center"
            alignItems="center"

        >
            <Grid
                xs={11}
                sm={8}
                md={6}
                lg={4}
            >
                <Card
                    component="form"
                    method="POST"
                    action={route('verification.resend')}
                >
                    <CsrfToken />
                    <CardContent>
                        <Typography
                            gutterBottom
                            variant="h5"
                            component="div"
                        >
                            {t('verification.check')}
                        </Typography>
                        {blade('resent') && (
                            <Alert severity="success">
                                {t('verification.resent')}
                            </Alert>
                        )}
                        <Typography sx={{ mb: 1 }}>
                            {t('verification.notice')}
                        </Typography>
                        <Typography>
                            {t('verification.notReceived')}
                        </Typography>
                    </CardContent>
                    <CardActions
                        sx={{
                            display: 'flex',
                            justifyContent: 'center',
                        }}
                    >
                        <Button
                            sx={{ display: 'inline' }}
                            size="small"
                            type="submit"
                            variant="contained"
                        >
                            {t('verification.resendButton')}
                        </Button>
                    </CardActions>
                </Card>
            </Grid>
        </Grid>
    );
};

export default Verify;
