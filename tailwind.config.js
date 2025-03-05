import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './vendor/laravel/jetstream/**/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php', // Include all view Blade templates
    './resources/**/*.js', // Include JavaScript files for Tailwind directives (optional)
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#39a900',
        'secondary': '#98fe58',
        'tertiary': '#f2ffe5',
        'alternate': '#29690b',

        // Text colors
        'primary-text': 'black',
        'secondary-text': '#5d5d5d',
        'alternate-text': '#919191',

        // Background colors
        'primary-background': '#fff',
        'secondary-background': '#E6E6E6',
        'alternate-background': '#04324d',
        'alternate-background-100': '#def3ff',

        // Custom colors
        'color-success': '#52d726',
        'color-error': '#e74c3c',
        'color-warning': '#f1c40f',
        'color-info': '#ffffff',
        'background-gray': '#B5C4CB',
      },
    },
  },
  plugins: [forms, typography],
  corePlugins: {
    preflight: false, // Desactiva los estilos predeterminados de Tailwind
  },

};
