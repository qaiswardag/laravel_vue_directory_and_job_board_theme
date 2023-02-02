<script setup>
import { Head, router } from "@inertiajs/vue3";
import Banner from "@/Components/Banners/Banner.vue";
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
        <div class="my-6 pr-12">
            <nav
                class="myPrimaryParagraph font-medium text-base flex justify-end items-center myPrimaryGap"
            >
                <Link
                    :href="route('home')"
                    :class="{
                        'myPrimaryLink text-base': route().current('home'),
                    }"
                >
                    Home
                </Link>
                <Link
                    :href="route('login')"
                    :class="{
                        'myPrimaryLink text-base': route().current('login'),
                    }"
                >
                    Login
                </Link>
                <Link
                    :href="route('register')"
                    :class="{
                        'myPrimaryLink text-base': route().current('register'),
                    }"
                >
                    Register
                </Link>
            </nav>
        </div>

        <div class="min-h-screen bg-gray-100">
            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
