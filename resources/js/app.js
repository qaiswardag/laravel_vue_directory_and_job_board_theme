import "./bootstrap";
import "../css/app.css";
import store from "./store/index";
import "vue-website-page-builder/style.css";

import { createApp, h } from "vue";
import { createInertiaApp, Link } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import { pageBuilder } from "vue-website-page-builder";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "myself";
createInertiaApp({
    title: (title) => `${appName} | ${title}`,
    // Disable or enable Inertia's default loading indicato

    progress: false,

    //
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(store)
            .use(plugin)
            .use(pageBuilder)
            .component("Link", Link)
            .component("Head", Head)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
});
