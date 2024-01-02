<script setup>
import { router } from "@inertiajs/vue3";
import FooterGuests from "@/Components/Footer/FooterGuests.vue";
import GuestsNavbar from "@/Components/Navbars/GuestsNavbar.vue";
import ApplicationLogo from "@/Components/Logos/ApplicationLogo.vue";
import Banner from "@/Components/Banners/Banner.vue";
import { ref } from "vue";

// DOM is loaded
const isDOMLoaded = ref(false);

router.on("start", () => {
    isDOMLoaded.value = true;
    console.log(`START:`, isDOMLoaded.value);
});

router.on("finish", (event) => {
    isDOMLoaded.value = false;
    console.log(`FINISH...:`, isDOMLoaded.value);
});
</script>

<template>
    <div
        class="h-16 top-16 fixed left-0 right-0 z-50 flex flex-shrink-0 justify-between items-center bg-white border-b-2 border-gray-100"
    >
        <p
            class="text-center bg-red-100 w-full h-full py-12 flex items-center justify-center font-medium"
        >
            isDOMLoaded: {{ isDOMLoaded }}
        </p>
    </div>

    <div>
        <nav
            class="h-16 sticky top-0 z-30 flex flex-shrink-0 justify-between items-center bg-white border-b-2 border-gray-100"
        >
            <div class="flex flex-shrink-0 items-center px-4 justify-between">
                <ApplicationLogo></ApplicationLogo>
            </div>
            <GuestsNavbar></GuestsNavbar>
        </nav>
        <slot name="header" />

        <div class="mx-auto min-h-screen">
            <Banner />
            <main>
                <slot />
            </main>
        </div>
    </div>
    <FooterGuests></FooterGuests>
</template>
