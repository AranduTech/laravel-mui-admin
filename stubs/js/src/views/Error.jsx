import React from 'react';

import Button from '@mui/material/Button';
import Divider from '@mui/material/Divider';
import Grid from '@mui/material/Unstable_Grid2';
import Stack from '@mui/material/Stack';
import Typography from '@mui/material/Typography';

import { useTranslation } from 'react-i18next';
import { useRouteError } from 'react-router-dom';
import useWindowHeight from '../hooks/useWindowHeight';

const Error = () => {
    const { t } = useTranslation();
    const error = useRouteError();
    const height = useWindowHeight();

    return (
        <Grid
            container
            spacing={2}
            justifyContent="center"
            alignItems="center"
            height={height}
            disableEqualOverflow
        >
            <Grid
                xs={11}
                md={7}
                lg={5}
                display="flex"
                flexDirection="column"
                justifyContent="center"
                alignItems="center"
            >
                <Stack>
                    <Typography
                        variant="h4"
                        textAlign="center"
                        sx={{ mb: 3 }}
                    >
                        {t('error')}
                    </Typography>
                    <Divider />
                    <Typography
                        variant="body1"
                        textAlign="center"
                        sx={{ mt: 3 }}
                    >
                        {error.message}
                    </Typography>
                </Stack>
                <Button
                    component="a"
                    href="/"
                >
                    {t('navigate.back')}
                </Button>
            </Grid>
        </Grid>
    );
};

export default Error;
