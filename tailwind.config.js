import defaultTheme from 'tailwindcss/defaultTheme';
/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'light',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        "./vendor/livewire/flux-pro/stubs/**/*.blade.php",        
        "./vendor/livewire/flux/stubs/**/*.blade.php",
    ],
    theme: {
        extend: {
            screens: {  
                'lg': '1280px',
                'xl': '1440px',
                '2xl': '1536px',
                '3xl': '1600px',
                '4xl': '1920px',
            },
            container: {
                padding: {
                    // DEFAULT: '1rem',
                    // sm: '2rem',
                    // lg: '4rem',
                    // xl: '5rem',
                    '2xl': '5rem',
                },
            },
            maxWidth: {
                '9xl': '120rem',
                '8xl': '96rem'
            },
            borderRadius: {
                xl: '12px', // Sudut lebih lembut
            },
            boxShadow: {
                modern: '0px 4px 10px rgba(0, 0, 0, 0.1)', // Shadow lebih modern
            },
            fontFamily: {            
                sans: ['Nunito', 'sans-serif'],        
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/line-clamp'),
    ],
};
