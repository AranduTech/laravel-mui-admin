import * as React from 'react';

import Divider from '@mui/material/Divider';

import { Link, Outlet } from 'react-router-dom';

import SideMenuLayout from '@arandu/laravel-mui-admin/lib/components/SideMenuLayout';

import { route, t, auth } from '@arandu/laravel-mui-admin';

/**
 * Add items no menu aqui.
 */
const navMenuItems = [
    {
        key: 1,
        text: t('navigate.home'),
        icon: 'homeOutlined',
        ListItemButtonProps: {
            component: Link,
            to: route('home'),

        },
    },
    {
        element: (
            <Divider
                key="2"
                sx={{ my: 1 }}
            />
        ),
    },

];

const bottomMenuItems = [
    {
        element: (
            <Divider
                key="2"
                sx={{ mb: 1 }}
            />
        ),
    },
    {
        key: 3,
        text: t('navigate.logout'),
        icon: 'logout',
        ListItemButtonProps: { onClick: () => auth().logout() },
    },
];

const Authenticated = () => (
    <SideMenuLayout
        navMenuItems={navMenuItems}
        bottomMenuItems={bottomMenuItems}
    >
        <Outlet />
    </SideMenuLayout>
);

export default Authenticated;

