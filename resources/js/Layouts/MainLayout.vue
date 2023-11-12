<script setup>
import { router } from "@inertiajs/vue3";
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";
import Flash from "@/Components/Actions/Flash.vue";
import { ref, computed, watch, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

import { useStore } from "vuex";

defineProps({
    title: {
        required: false,
    },
});

const store = useStore();

// DOM is loaded
const isDOMLoaded = ref(false);

router.on("start", () => {
    isDOMLoaded.value = true;
});
router.on("finish", () => {
    isDOMLoaded.value = false;
});

const shouldShowFlash = ref(false);

const flashState = computed(() => {
    return usePage().props.flash;
});

const getIsLoading = computed(() => {
    return store.getters["user/getIsLoading"];
});

watch(flashState, (newValue) => {
    if (newValue) {
        shouldShowFlash.value = true;
    }
    setTimeout(() => {
        shouldShowFlash.value = false;
    }, 1000);
});

const maintenance = true;
</script>

<template>
    <Head>
        <title>{{ title }}</title>
        <meta type="description" content="myself" head-key="description" />
    </Head>
    <p class="my-8 py-4 border-2 border-red-300 text-center w-64 mx-auto">
        Check component MainLayout
    </p>

    <Flash
        v-show="shouldShowFlash"
        :showCloseButton="false"
        :flash="$page.props.flash"
    ></Flash>
    <FullScreenSpinner v-if="isDOMLoaded || getIsLoading"></FullScreenSpinner>
    <slot />
</template>
