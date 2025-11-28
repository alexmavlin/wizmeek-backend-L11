function setTheHost() {
    let host = '';

    switch (window.location.hostname) {
        case 'localhost':
            host = 'http://localhost:8876';
            break;
        case 'dev-app.wizmeek.com':
            host = 'https://dev-app.wizmeek.com';
            break;
        default:
            host = 'http://localhost:8876';
            break;
    }

    return host;
}

export const URL = {
    HOST: setTheHost(),
    VIDEO_SEARCH: {
        FOR_LOADER: '/admin/landing/search',
        EDITORS_PICK: '/admin/homepage/search-editors-pick',
        NEW: '/admin/homepage/search-new',
        THROWBACK: '/admin/homepage/search-throwback',
    }
}