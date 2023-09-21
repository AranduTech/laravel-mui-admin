import React from 'react';

import Typography from '@mui/material/Typography';
import Stack from '@mui/material/Stack';

import { useTranslation } from 'react-i18next';

import ModelForm from '@arandu/laravel-mui-admin/lib/components/Form/ModelForm';

import { toast, auth } from '@arandu/laravel-mui-admin';

// import { User } from '../../models';

const Profile = () => {
    const { t } = useTranslation();

    const user = React.useMemo(() => auth().user(), []);

    return (
        <Stack>
            <Typography
                variant="h6"
                sx={{ mb: 2 }}
            >
                {t('profile.title')}
            </Typography>
            <ModelForm
                item={user}
                spacing={2}
                onSuccess={() => toast.success(t('profile.success'))}
                schema="default"
                onError={(error) => {
                    toast.error(error.response?.data.message || error.message);
                }}
            />
        </Stack>
    );
};

export default Profile;
