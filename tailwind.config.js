// tailwind.config.js
module.exports = {
    purge: [
        './views/**/*.view.php',
        './public/js/**/*.js',
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {},
    },
    variants: {
        extend: {},
    },
    plugins: [],
}