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
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
    safelist: [
        'bg-red-100', 'bg-blue-100', 'bg-green-100', 'bg-yellow-100',
        'border-red-500', 'border-blue-500', 'border-green-500', 'border-yellow-500',
        'text-red-700', 'text-blue-700', 'text-green-700', 'text-yellow-700',
    ]
};
