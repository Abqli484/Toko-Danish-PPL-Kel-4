import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./node_modules/flowbite/**/*.js", // Tambahkan path Flowbite
    ],

    darkMode: 'class', // Aktifkan dark mode (opsional, dari Flowbite)

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans], // Ganti 'Figtree' dengan 'Inter' (font default Flowbite)
            },
            colors: {
                primary: { // Tambahkan palette warna Flowbite
                    50: "#eff6ff",
                    100: "#dbeafe",
                    200: "#bfdbfe",
                    300: "#93c5fd",
                    400: "#60a5fa",
                    500: "#3b82f6",
                    600: "#2563eb",
                    700: "#1d4ed8",
                    800: "#1e40af",
                    900: "#1e3a8a",
                    950: "#172554"
                }
            },
        },
    },

    plugins: [
        forms,
        require('flowbite/plugin'), // Tambahkan plugin Flowbite
    ],
};

