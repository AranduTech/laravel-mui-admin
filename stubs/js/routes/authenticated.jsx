import React from 'react';

import { route, Suspense } from '@arandu/laravel-mui-admin';

import Error from '../views/Error';

export default () => [
    {
        path: route('home'),
        element: (
            <Suspense>
                {React.lazy(() => import('../views/Layouts/Authenticated'))}
            </Suspense>
        ),
        errorElement: <Error />,
        children: [
            {
                index: true,
                element: (
                    <Suspense>
                        {React.lazy(() => import('../views/Home'))}
                    </Suspense>
                ),
            },
            route.exists('verification.notice') && {
                path: route('verification.notice'),
                element: (
                    <Suspense>
                        {React.lazy(() => import('../views/Auth/Verify'))}
                    </Suspense>
                ),
            },
            route.exists('profile') && {
                path: route('profile'),
                element: (
                    <Suspense>
                        {React.lazy(() => import('../views/User/Profile'))}
                    </Suspense>
                ),
            },
        ],
    },
];
