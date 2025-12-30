function updateFavicon(theme) {
    const favicon = document.querySelector('link[rel="icon"]') ||
                    document.createElement('link');

    favicon.rel = 'icon';
    favicon.type = 'image/png';
    favicon.href = theme === 'dark' ? '/favicon-dark.png' : '/favicon.png';

    if (!document.querySelector('link[rel="icon"]')) {
        document.head.appendChild(favicon);
    }
}

// Listen for system theme changes
if (window.matchMedia) {
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

    // Set initial favicon
    updateFavicon(mediaQuery.matches ? 'dark' : 'light');

    // Listen for changes
    mediaQuery.addEventListener('change', (e) => {
        updateFavicon(e.matches ? 'dark' : 'light');
    });
}
