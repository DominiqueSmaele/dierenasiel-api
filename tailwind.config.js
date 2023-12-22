import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './vendor/wire-elements/pro/config/wire-elements-pro.php',
        './vendor/wire-elements/pro/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
    ],

    theme: {
        boxShadow: {
            'light': '0px 4px 4px rgba(2, 51, 115, 0.08)',
            'dark': '0px 4px 4px rgba(0, 0, 0, 0.15)',
            'none': '0 0 #000000',
        },

        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            'white': '#FFFFFF',
            'black': '#4A4D57',
            'blue': {
                'dark': '#023373',
                'base': '#2A4D8C',
                'light': '#5D7CA6',
                'lightest': '#F4F7FC',
            },
            'gray': {
                'dark': '#9FADBF',
                'base': '#BCC5D2',
                'light': '#DEE3E9'
            },
            'orange': {
                'dark': '#FC7B49',
                'base': '#FC8D62',
                'light': '#FC9F7B'
            },
            'red': {
                'base': '#EF6161',
            },
            'green': {
                'base': '#1EE372',
            },
        },

        fontFamily: {
            'highlight-sans': ['Barlow Condensed', ...defaultTheme.fontFamily.sans],
            'sans': ['Open Sans', ...defaultTheme.fontFamily.sans],
        },
    },

    plugins: [forms, typography],
};
