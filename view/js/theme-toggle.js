/**
 * theme-toggle.js — SmartFood Dark / Light Mode Controller
 *
 * Reads preference from localStorage ('smartfood-theme'),
 * falls back to prefers-color-scheme, sets data-theme on <html>.
 */

(function() {
    'use strict';

    var STORAGE_KEY = 'smartfood-theme';
    var html = document.documentElement;

    /**
     * Determine the initial theme.
     * Priority: localStorage > prefers-color-scheme > 'light'
     */
    function getInitialTheme() {
        var stored = localStorage.getItem(STORAGE_KEY);
        if (stored === 'dark' || stored === 'light') {
            return stored;
        }
        // Respect OS preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        return 'light';
    }

    /**
     * Apply a theme without transition (used on initial load to avoid flash).
     */
    function applyTheme(theme) {
        html.setAttribute('data-theme', theme);
        localStorage.setItem(STORAGE_KEY, theme);
        updateToggleButtons(theme);
    }

    /**
     * Toggle between light and dark, with smooth transition.
     */
    function toggleTheme() {
        var current = html.getAttribute('data-theme') || 'light';
        var next = (current === 'dark') ? 'light' : 'dark';

        // Enable transition
        html.classList.add('theme-transition');
        applyTheme(next);

        // Remove transition class after animation completes
        setTimeout(function() {
            html.classList.remove('theme-transition');
        }, 400);
    }

    /**
     * Update all toggle buttons' active state (aria-label).
     */
    function updateToggleButtons(theme) {
        var buttons = document.querySelectorAll('.theme-toggle');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].setAttribute('aria-label',
                theme === 'dark' ? 'Passer en mode clair' : 'Passer en mode sombre'
            );
        }
    }

    // --- Initialise on load ---
    // Apply immediately (before paint to prevent flash)
    var initialTheme = getInitialTheme();
    applyTheme(initialTheme);

    // Bind click handlers once DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        var buttons = document.querySelectorAll('.theme-toggle');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener('click', toggleTheme);
        }
        // Re-apply to update button states after DOM load
        updateToggleButtons(html.getAttribute('data-theme') || 'light');
    });

    // Listen for OS theme changes
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            // Only follow OS change if user hasn't manually chosen
            if (!localStorage.getItem(STORAGE_KEY)) {
                applyTheme(e.matches ? 'dark' : 'light');
            }
        });
    }

})();
