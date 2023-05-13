<script setup>
import { Head, router } from "@inertiajs/vue3";
import Banner from "@/Components/Banners/Banner.vue";
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";
import GuestsNavbar from "../Components/Navbars/GuestsNavbar.vue";

import { ref } from "vue";

defineProps({
    title: String,
});

// is loaded
const isLoaded = ref(false);

router.on("start", () => {
    isLoaded.value = true;
});
router.on("finish", () => {
    isLoaded.value = false;
});
</script>

<template>
    <!-- <div v-if="isLoaded">
        <FullScreenSpinner></FullScreenSpinner>
    </div> -->
    <div>
        <Head>
            <title>{{ title }}</title>
            <!-- head-key makes sure to only add on meta key -->
            <meta
                type="description"
                content="myself.ae Fashion and Jobs"
                head-key="description"
            />
        </Head>
        <Banner />

        <!-- Topbar - start -->
        <div
            class="h-16 sticky top-0 z-10 flex flex-shrink-0 justify-between items-center bg-white border-b-2 border-red-300"
        >
            <div class="flex flex-shrink-0 items-center px-4 justify-between">
                <Link :href="route('home')" class="myPrimaryLink">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-24 my-logo"
                        viewBox="0 0 35.18 11.33"
                    >
                        <path
                            d="m0,2.62h.82v1.1c.29-.41.58-.7.88-.89.41-.25.85-.38,1.31-.38.31,0,.6.06.88.18s.5.28.68.48c.18.2.33.49.46.86.27-.5.61-.88,1.02-1.14s.85-.39,1.32-.39c.44,0,.83.11,1.17.33.34.22.59.53.75.93s.25,1,.25,1.79v3.48h-.84v-3.48c0-.68-.05-1.15-.15-1.41s-.26-.46-.5-.62c-.24-.16-.52-.23-.84-.23-.4,0-.76.12-1.09.35-.33.23-.57.55-.72.94s-.23,1.04-.23,1.96v2.5h-.82v-3.26c0-.77-.05-1.29-.14-1.57s-.26-.5-.5-.66-.52-.25-.85-.25c-.38,0-.73.11-1.06.34s-.57.54-.73.92-.24.98-.24,1.78v2.7h-.82V2.62Z"
                            fill="#231f20"
                        />
                        <path
                            d="m10.58,2.62h.87l2.19,4.91,2.13-4.91h.87l-3.8,8.71h-.87l1.23-2.81-2.62-5.89Z"
                            fill="#231f20"
                        />
                        <path
                            d="m20.9,3.32l-.53.54c-.44-.43-.87-.64-1.29-.64-.27,0-.49.09-.68.26-.19.18-.29.38-.29.62,0,.21.08.4.23.59.16.19.49.42.99.67.61.32,1.03.62,1.25.91.22.3.32.63.32,1,0,.52-.18.97-.55,1.33s-.83.54-1.38.54c-.37,0-.72-.08-1.05-.24s-.61-.38-.83-.66l.52-.59c.42.47.86.71,1.33.71.33,0,.61-.11.84-.32s.35-.46.35-.74c0-.23-.08-.44-.23-.63-.15-.18-.5-.41-1.03-.68-.58-.3-.97-.59-1.17-.88s-.31-.62-.31-.99c0-.48.17-.89.5-1.21.33-.32.75-.48,1.25-.48.59,0,1.18.29,1.77.86Z"
                            fill="#231f20"
                        />
                        <path
                            d="m27.72,6.88l.69.36c-.23.45-.49.8-.79,1.08-.3.27-.63.48-1,.62-.37.14-.79.21-1.26.21-1.04,0-1.85-.34-2.44-1.02s-.88-1.45-.88-2.31c0-.81.25-1.53.74-2.16.63-.8,1.47-1.21,2.53-1.21s1.96.41,2.6,1.24c.46.58.7,1.31.7,2.18h-5.74c.02.74.25,1.35.71,1.82.46.47,1.02.71,1.7.71.32,0,.64-.06.95-.17s.57-.26.78-.45c.22-.19.45-.49.7-.9Zm0-1.72c-.11-.44-.27-.79-.48-1.05-.21-.26-.49-.47-.83-.63-.34-.16-.71-.24-1.09-.24-.63,0-1.16.2-1.61.6-.33.29-.58.73-.75,1.32h4.75Z"
                            fill="#231f20"
                        />
                        <path d="m30.03.16h.82v8.84h-.82V.16Z" fill="#231f20" />
                        <path
                            d="m35.18.16v.8c-.3-.13-.56-.19-.77-.19-.16,0-.29.03-.4.1s-.17.14-.2.22-.04.31-.04.67v.86s1.33,0,1.33,0v.71h-1.33v5.67h-.82V3.33h-.84v-.71h.84v-1c0-.46.04-.78.13-.98s.23-.35.43-.47.44-.18.73-.18c.27,0,.58.05.96.16Z"
                            fill="#231f20"
                        />
                    </svg>
                </Link>
            </div>
            <GuestsNavbar></GuestsNavbar>
        </div>

        <slot name="header" />
        <div class="mx-auto min-h-screen">
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
