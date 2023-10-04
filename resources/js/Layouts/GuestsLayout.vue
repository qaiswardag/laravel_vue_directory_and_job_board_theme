<script setup>
import { router } from "@inertiajs/vue3";
import Banner from "@/Components/Banners/Banner.vue";
import GuestsNavbar from "@/Components/Navbars/GuestsNavbar.vue";
import FooterUniversal from "@/Components/Footer/FooterUniversal.vue";
import ApplicationLogo from "@/Components/Logos/ApplicationLogo.vue";
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";
import { ref } from "vue";

// DOM is loaded
const isDOMLoaded = ref(false);

router.on("start", () => {
    isDOMLoaded.value = true;
});
router.on("finish", () => {
    isDOMLoaded.value = false;
});

defineProps({
    title: {
        required: false,
    },
});
</script>

<template>
    <div>
        <!-- head-key makes sure to only add on meta key -->
        <!-- <Head>
            <title>{{ title }}</title>
            <meta type="description" content="myself" head-key="description" />
        </Head> -->

        <FullScreenSpinner v-if="isDOMLoaded"></FullScreenSpinner>

        <Banner />
        <!-- Topbar - start -->
        <nav
            class="h-16 sticky top-0 z-10 flex flex-shrink-0 justify-between items-center bg-white border-b-2 border-gray-100"
        >
            <div class="flex flex-shrink-0 items-center px-4 justify-between">
                <ApplicationLogo></ApplicationLogo>
            </div>
            <GuestsNavbar></GuestsNavbar>
        </nav>

        <slot name="header" />

        <div class="mx-auto min-h-screen">
            <main>
                <slot />
            </main>
        </div>
    </div>
    <FooterUniversal></FooterUniversal>
</template>
