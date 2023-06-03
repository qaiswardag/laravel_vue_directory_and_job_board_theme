<script setup>
import { router } from "@inertiajs/vue3";
import Banner from "@/Components/Banners/Banner.vue";
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";
import GuestsNavbar from "@/Components/Navbars/GuestsNavbar.vue";
import FooterUniversal from "@/Components/Footer/FooterUniversal.vue";
import ApplicationLogo from "@/Components/Logos/ApplicationLogo.vue";

import { ref } from "vue";

defineProps({
    title: {
        required: false,
    },
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
        <!-- head-key makes sure to only add on meta key -->
        <!-- <Head>
            <title>{{ title }}</title>
            <meta type="description" content="myself" head-key="description" />
        </Head> -->

        <Banner />

        <!-- Topbar - start -->
        <div
            class="h-16 sticky top-0 z-10 flex flex-shrink-0 justify-between items-center bg-white border-b-2 border-myPrimaryBrandColor"
        >
            <div class="flex flex-shrink-0 items-center px-4 justify-between">
                <ApplicationLogo></ApplicationLogo>
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
    <FooterUniversal></FooterUniversal>
</template>
