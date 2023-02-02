const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        // colors: {
        //     transparent: "transparent",
        //     current: "currentColor",
        //     white: "#ffffff",
        //     purple: "#3f3cbb",
        //     midnight: "#121063",
        //     metal: "#565584",
        //     tahiti: "#3ab7bf",
        //     silver: "#ecebff",
        //     "bubble-gum": "#ff77e9",
        //     bermuda: "#78dcca",
        // },
        extend: {
            fontFamily: {
                sans: [
                    "Gill Sans",
                    "Century Gothic",
                    "Verdana",
                    ...defaultTheme.fontFamily.sans,
                ],
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
