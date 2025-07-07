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
    extend: {
  animation: {
    'fade-in': 'fadeIn 1s ease-out',
    'slide-in-left': 'slideInLeft 1s ease-out',
  },
  keyframes: {
    fadeIn: {
      '0%': { opacity: 0 },
      '100%': { opacity: 1 },
    },
    slideInLeft: {
      '0%': { transform: 'translateX(-50%)', opacity: 0 },
      '100%': { transform: 'translateX(0)', opacity: 1 },
    },
  },
}

};

