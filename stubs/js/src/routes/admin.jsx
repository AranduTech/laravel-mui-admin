import React from 'react';

import Suspense from '@arandu/laravel-mui-admin/lib/components/Suspense';
import { modelRepository, route } from '@arandu/laravel-mui-admin';

import Error from '../views/Error';

// import api from '../api';

export default [
    {
        path: route('home'),
        element: (
            <Suspense>
                {React.lazy(() => import('../views/Layouts/Admin'))}
            </Suspense>
        ),
        errorElement: <Error />,
        children: [
            {
                index: true,
                element: (
                    <Suspense>
                        {React.lazy(() => import('../views/Welcome'))}
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
            ...modelRepository.createWebRoutes({
                '*': () => (
                    <Suspense>
                        {React.lazy(() => import('@arandu/laravel-mui-admin/lib/components/RepositoryIndex'))}
                    </Suspense>
                ),
                // user: (action) => {
                //     // use this to override the default route or register other routes
                //     if (action === 'index') {
                //         return (
                //             <Suspense>
                //                 {React.lazy(() => import('../components/Example'))}
                //             </Suspense>
                //         );
                //     }
                // },
            }),
        ],
    },
];
