import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'light': '#edf2f9',
                'dark': '#152e4d',
                'darkodd': '#132944',
                'darker': '#12263f',
                'primary': '#4a148c',
                'primary-dark': '#070034',
                'secondary': '#ea8c30',
                'secondary-dark': '#c66d15',
            },
        },
    },

    plugins: [forms],
};
