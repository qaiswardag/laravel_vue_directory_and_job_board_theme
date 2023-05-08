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
            class="h-16 sticky top-0 z-10 flex flex-shrink-0 justify-end items-center bg-white border-b-2 border-red-300"
        >
            <GuestsNavbar></GuestsNavbar>
        </div>

        <slot name="header" />
        <div class="mx-auto max-w-7xl min-h-screen px-4 sm:px-6 md:px-8 pb-12">
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
