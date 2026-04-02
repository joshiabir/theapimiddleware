
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./resources/**/*.js",
        "./resources/**/*.vue",
       
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter'],
            },
        },
    },

    plugins:[],

};
