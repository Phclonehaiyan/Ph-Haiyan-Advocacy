import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                pine: {
                    50: '#edf6f1',
                    100: '#d7ebe0',
                    200: '#b0d7c0',
                    300: '#82bd9b',
                    400: '#54956f',
                    500: '#2f6a4e',
                    600: '#23513c',
                    700: '#18392a',
                    800: '#123323',
                    900: '#0f3d2e',
                    950: '#09281d',
                },
                teal: {
                    50: '#eefafb',
                    100: '#d7f1f4',
                    200: '#b5e4ea',
                    300: '#82ced8',
                    400: '#4eb0c0',
                    500: '#238fa5',
                    600: '#177485',
                    700: '#155d6b',
                    800: '#154c57',
                    900: '#173f49',
                },
                sand: {
                    50: '#fbfaf7',
                    100: '#f5efe6',
                    200: '#e8dcc9',
                    300: '#dbc6a8',
                    400: '#c8a57a',
                    500: '#b5875d',
                    600: '#98694a',
                    700: '#7a543d',
                    800: '#654637',
                    900: '#563d31',
                },
            },
            fontFamily: {
                sans: ['Manrope', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', 'Georgia', ...defaultTheme.fontFamily.serif],
            },
            boxShadow: {
                soft: '0 22px 55px -32px rgba(15, 61, 46, 0.35)',
                float: '0 32px 90px -40px rgba(15, 61, 46, 0.42)',
            },
        },
    },
    plugins: [],
};
