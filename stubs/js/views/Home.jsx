import React from 'react';

import Grid from '@mui/material/Unstable_Grid2';
import Typography from '@mui/material/Typography';

import { useTranslation } from 'react-i18next';

const Home = () => {
    const { t } = useTranslation();

    return (
        <Grid
            container
            disableEqualOverflow
            spacing={2}
        >
            <Grid xs={12}>
                <Typography variant="h6">
                    {t('welcome')}
                </Typography>
            </Grid>
        </Grid>
    );
};

export default Home;
