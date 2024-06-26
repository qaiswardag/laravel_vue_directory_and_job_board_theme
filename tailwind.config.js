const defaultTheme = require("tailwindcss/defaultTheme");
// const colors = require("tailwindcss/colors");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./index.html",
        "./src/**/*.{vue,js,ts,jsx,tsx}",
        "./public/json/**/*.json",
        "./public/**/*.html",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.{vue,js,ts,jsx,tsx}",
    ],

    theme: {
        extend: {
            colors: {
                ...defaultTheme.colors,
                // Custom colors
                myPrimaryBrandColor: "#000000",
                myPrimaryLinkColor: "#16a34a",
                // gray colors
                myPrimaryLightGrayColor: "#eff2f6",
                // for myPrimaryTag
                myPrimaryLightMediumGrayColor: "#dee6f0",

                // just a dark gray color
                myPrimaryMediumGrayColor: "#9ca3af",

                // use as black color
                myPrimaryDarkGrayColor: "#111827",

                myPrimaryErrorColor: "#d60000",
                myPrimarySuccesColor: "#16a34a",
            },
            fontFamily: {
                sans: [
                    "Jost",
                    "Raleway",
                    "'Cormorant'",
                    "sans-serif",
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
