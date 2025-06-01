import "./bootstrap";
import "../css/app.css";
import store from "./store/index";

import { createApp, h } from "vue";
import { createPinia } from "pinia";
import { createInertiaApp, Link } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";

const pinia = createPinia();

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "MyISSUE";
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
            .use(pinia)
            .use(store)
            .use(plugin)
            .component("Link", Link)
            .component("Head", Head)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
});
