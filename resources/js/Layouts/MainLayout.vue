<script setup>
import { router } from "@inertiajs/vue3";
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";
import Flash from "@/Components/Actions/Flash.vue";
import { ref, computed, watch, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

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

watch(flashState, (newValue) => {
    if (newValue) {
        shouldShowFlash.value = true;
    }
    setTimeout(() => {
        shouldShowFlash.value = false;
    }, 1000);
});

defineProps({
    title: {
        required: false,
    },
});
</script>

<template>
    <Head>
        <title>{{ title }}</title>
        <meta type="description" content="myself" head-key="description" />
    </Head>

    <Flash
        v-show="shouldShowFlash"
        :showCloseButton="false"
        :flash="$page.props.flash"
    ></Flash>

    <FullScreenSpinner v-if="isDOMLoaded"></FullScreenSpinner>
    <slot />
</template>
