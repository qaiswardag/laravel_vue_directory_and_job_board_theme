const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

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
        colors: {
            //
            // brand colors
            myPrimaryBrandColor: "#059669",
            //
            //
            // error color
            myErrorColor: "#d60000",
            // gray colors
            myPrimaryLightGrayColor: "#e2e8f0",
            myPrimaryMediumGrayColor: "#9ca3af",
            myPrimaryDarkGrayColor: "#111827",

            //

            transparent: "transparent",
            current: "currentColor",
            black: colors.black,
            white: colors.white,
            gray: colors.slate,
            green: colors.emerald,
            purple: colors.violet,
            indigo: colors.indigo,
            yellow: colors.amber,
            red: colors.red,
            fuchsia: colors.fuchsia,
            pink: colors.fuchsia,
            violet: colors.violet,
        },
        extend: {
            fontFamily: {
                sans: [
                    "Gill Sans",
                    "Gill Sans",
                    "Century Gothic",
                    "Verdana",
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
