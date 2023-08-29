import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                roboto: ['Roboto-Regular'],
                robotoBlack: ['Roboto-Black'],
                robotoBold: ['Roboto-Bold'],
                robotoLight: ['Roboto-Light'],
                robotoBlackItalic: ['Roboto-BlackItalic'],
                robotoBoldItalic: ['Roboto-BoldItalic'],
            },
        },
    },
    darkMode: 'class',
    plugins: [forms],
};
