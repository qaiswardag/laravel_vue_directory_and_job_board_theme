<script setup>
import { router } from "@inertiajs/vue3";
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";
import Flash from "@/Components/Actions/Flash.vue";
import { ref, computed, watch } from "vue";
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
    console.log(`START:`, isDOMLoaded.value);
});

router.on("finish", (event) => {
    isDOMLoaded.value = false;
    console.log(`FINISH...:`, isDOMLoaded.value);
});
//
//
//
//
//

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

    <!-- <teleport to="body">
        <FullScreenSpinner
            v-if="isDOMLoaded || getIsLoading"
        ></FullScreenSpinner>
    </teleport> -->

    <slot />
</template>
