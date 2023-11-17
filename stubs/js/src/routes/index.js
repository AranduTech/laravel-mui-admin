import { createBrowserRouter } from 'react-router-dom';

export default async (rendererName) => {
    if (!['admin', 'authenticated', 'guest'].includes(rendererName)) {
        throw new Error(`Could not find router for renderer: ${rendererName}`);
    }

    if ('admin' === rendererName) {
        // Load the admin routes.
        const { default: admin } = await import(/* webpackChunkName: "admin-router" */ './admin');

        return createBrowserRouter(admin());
    }

    if ('authenticated' === rendererName) {
        // Load the authenticated routes.
        const { default: authenticated } = await import(/* webpackChunkName: "authenticated-router" */ './authenticated');

        return createBrowserRouter(authenticated());
    }

    // Load the guest routes.
    const { default: guest } = await import(/* webpackChunkName: "guest-router" */ './guest');

    return createBrowserRouter(guest());
};
