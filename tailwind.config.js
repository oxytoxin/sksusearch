const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')
module.exports = {
    presets: [
      
        require('./vendor/wireui/wireui/tailwind.config.js')
    ],
    content: [
        './resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php'
    ],
    theme: {
        extend: {
            gridTemplateRows:{
                '7': 'repeat(7, minmax(0, 1fr))',
                '8': 'repeat(8, minmax(0, 1fr))',
                '9': 'repeat(9, minmax(0, 1fr))',
                '10': 'repeat(10, minmax(0, 1fr))',
            },
            spacing :{
                'screen-70':"70vh",
                'screen-80':"80vh"
            },
            fontSize:{
                '12':'12px',
                '10':'10px',
                '8':'8px',
            },
            screens: {
                'print': { 'raw': 'print' },
            },
            fontFamily: {
                sans: ['Rubik', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                danger: colors.rose,
                success: colors.green,
                warning: colors.yellow,
                "primary-bg": "#0c6600",
                "primary-bg-alt": "#cee0cc",
                "secondary-bg": "#cee0cc",
                "secondary-bg-alt": "#cee0cc",
                "primary-text": "#cee0cc",
                "primary-text-alt": "#cee0cc",
                "secondary-text": "#cee0cc",
                "orange-ripe": "#cee0cc",
                "orange-ripe-light": "#cee0cc",
                "main-bg": "#cee0cc",


                'primary': {
                    100: "#cee0cc",
                    200: "#9ec299",
                    300: "#6da366",
                    400: "#3d8533",
                    500: "#0c6600",
                    600: "#0a5200",
                    700: "#073d00",
                    800: "#052900",
                    900: "#021400"
                },
                'primary-alt': {
                    100: "#dfe2d7",
                    200: "#bfc4af",
                    300: "#a0a788",
                    400: "#808960",
                    500: "#606c38",
                    600: "#4d562d",
                    700: "#3a4122",
                    800: "#262b16",
                    900: "#13160b"
                },
                'secondary': {
                    100: "#cee0cc",
                    200: "#9ec299",
                    300: "#6da366",
                    400: "#3d8533",
                    500: "#0c6600",
                    600: "#0a5200",
                    700: "#073d00",
                    800: "#052900",
                    900: "#021400"
                },
                'secondary-alt': {
                    100: "#ffe7d9",
                    200: "#ffcfb3",
                    300: "#ffb88c",
                    400: "#ffa066",
                    500: "#ff8840",
                    600: "#cc6d33",
                    700: "#995226",
                    800: "#66361a",
                    900: "#331b0d"
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/line-clamp'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
