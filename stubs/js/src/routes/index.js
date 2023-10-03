import { createBrowserRouter } from 'react-router-dom';

export default async (rendererName) => {
    if (!['admin', 'authenticated', 'guest'].includes(rendererName)) {
        throw new Error(`Could not find router for renderer: ${rendererName}`);
    }

    if ('admin' === rendererName) {
        // Load the admin app.
        const admin = await import(/* webpackChunkName: "admin-router" */ './admin');

        return createBrowserRouter(admin.default());
    }

    if ('authenticated' === rendererName) {
        // Load the authenticated app.
        const authenticated = await import(/* webpackChunkName: "authenticated-router" */ './authenticated');

        return createBrowserRouter(authenticated.default());
    }

    // Load the guest app.
    const guest = await import(/* webpackChunkName: "guest-router" */ './guest');

    return createBrowserRouter(guest.default());
};
