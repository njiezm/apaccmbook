import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        colors: {
            'cardinal': '#b91c1c',
            'cardinal-hover': '#991b1b',
            'cardinal-light': '#dc2626',
            'anthracite': '#2d3139',
            'cream': '#f6f3ef',
            'white': '#ffffff',
            'black': '#000000',
            'text-primary': '#1a1a1a',
            'text-secondary': '#444',
            'text-tertiary': '#555',
            'border-light': '#eee',
            'border-gray': '#e5e7eb',
            'bg-footer': '#f9fafb',
            'transparent': 'transparent',
            'gray': {
                50: '#f9fafb',
                100: '#f3f4f6',
                200: '#e5e7eb',
                300: '#d1d5db',
                400: '#9ca3af',
                500: '#6b7280',
                600: '#4b5563',
                700: '#374151',
                800: '#1f2937',
                900: '#111827',
            },
        },
        fontFamily: {
            'sans': ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            'serif': ['Cinzel', ...defaultTheme.fontFamily.serif],
        },
        extend: {
            spacing: {
                'narthex': '4px',
            },
            borderRadius: {
                'arch': '50% 50% 0 0 / 40% 40% 0 0',
            },
            boxShadow: {
                'soft': '0 2px 8px rgba(0, 0, 0, 0.08)',
                'card': 'inset 0 0 0 1px rgba(0, 0, 0, 0.05)',
            },
            letterSpacing: {
                'tracked': '0.05em',
                'tracked-wide': '0.3em',
            },
        },
    },

    plugins: [forms],
};
