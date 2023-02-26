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
            <nav class="flex justify-end items-center myPrimaryGap">
                <Link
                    :href="route('dashboard')"
                    :class="{
                        'myPrimaryLink myPrimaryParagraph':
                            route().current('dashboard'),
                    }"
                    class="myPrimaryParagraph"
                >
                    Dashboard
                </Link>
                <Link
                    :href="route('users')"
                    :class="{
                        'myPrimaryParagraph myPrimaryParagraph':
                            route().current('users'),
                    }"
                    class="myPrimaryParagraph"
                >
                    Users
                </Link>
                <Link
                    :href="route('posts')"
                    :class="{
                        'myPrimaryParagraph myPrimaryParagraph':
                            route().current('posts'),
                    }"
                    class="myPrimaryParagraph"
                >
                    Posts
                </Link>

                <Link
                    :href="route('login')"
                    :class="{
                        'myPrimaryParagraph myPrimaryParagraph':
                            route().current('login'),
                    }"
                    class="myPrimaryParagraph"
                >
                    Login
                </Link>
                <Link
                    :href="route('register')"
                    :class="{
                        'myPrimaryParagraph myPrimaryParagraph':
                            route().current('register'),
                    }"
                    class="myPrimaryParagraph"
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
