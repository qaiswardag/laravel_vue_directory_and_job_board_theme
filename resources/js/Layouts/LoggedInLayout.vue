<script setup>
import { Head, router } from "@inertiajs/vue3";
import Banner from "@/Components/Banner.vue";
import Navbar from "@/Components/Navbars/Navbar.vue";
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";

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
    <div v-if="isLoaded">
        <FullScreenSpinner></FullScreenSpinner>
    </div>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gray-100">
            <Navbar></Navbar>
            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
