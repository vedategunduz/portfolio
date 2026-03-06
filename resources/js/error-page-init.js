/**
 * Error Page Initialization
 * This file is loaded only on error pages
 */

import { createIcons } from 'lucide';
import {
    Sun,
    Moon,
    Home,
    ArrowLeft,
    RefreshCw,
    LogIn,
    CheckCircle,
} from 'lucide';
import { initThemeToggle } from './core/theme-toggle.js';
import { initErrorPage } from './features/error-page.js';

const errorPageIcons = {
    Sun,
    Moon,
    Home,
    ArrowLeft,
    RefreshCw,
    LogIn,
    CheckCircle,
};

// Initialize theme toggle for error pages
initThemeToggle(createIcons, errorPageIcons);

// Initialize premium error page features
initErrorPage();

// Create icons
createIcons({
    attrs: { width: 16, height: 16 },
    icons: errorPageIcons,
});

// Make available globally for dynamic icon creation
window.createIcons = createIcons;
window.lucideIcons = errorPageIcons;
