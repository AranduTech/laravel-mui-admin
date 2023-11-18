import * as React from 'react';

import Divider from '@mui/material/Divider';

import { NavLink as Link, Outlet } from 'react-router-dom';

import { route, t, auth, SideMenuLayout } from '@arandu/laravel-mui-admin';

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
    route.exists('admin.user.index') && {
        key: 2,
        text: t('models.user.plural'),
        icon: 'peopleOutline',
        ListItemButtonProps: {
            component: Link,
            to: route('admin.user.index'),
        },
    },
    route.exists('admin.role.index') && {
        key: 3,
        text: t('models.role.plural'),
        icon: 'security',
        ListItemButtonProps: {
            component: Link,
            to: route('admin.role.index'),
        },
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

const Admin = () => (
    <SideMenuLayout
        navMenuItems={navMenuItems.filter(Boolean)}
        bottomMenuItems={bottomMenuItems}
    >
        <Outlet />
    </SideMenuLayout>
);

export default Admin;

