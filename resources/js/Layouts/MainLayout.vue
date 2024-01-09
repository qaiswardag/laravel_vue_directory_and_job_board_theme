<script setup>
import { router } from "@inertiajs/vue3";
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";
import Flash from "@/Components/Actions/Flash.vue";
import { ref, computed, watch, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useStore } from "vuex";
import CookieConsentModal from "@/Components/Modals/CookieConsentModal.vue";

defineProps({
    title: {
        required: false,
    },
});

const store = useStore();

const getIsLoading = computed(() => {
    return store.getters["user/getIsLoading"];
});

// DOM is loaded
router.on("start", () => {
    store.commit("user/setIsLoading", true);
});

router.on("finish", (event) => {
    store.commit("user/setIsLoading", false);
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

//
//
//
const showCookieModal = ref(false);
const cookieName = "cookieConsent";

const handleCookieModal = function () {
    showCookieModal.value = false;
};

const checkACookieExists = function (cookieName) {
    if (
        document.cookie
            .split(";")
            .some((item) => item.trim().startsWith(`${cookieName}=`))
    ) {
        showCookieModal.value = false;
    } else {
        showCookieModal.value = true;
    }
};

//
//
//
//
onMounted(() => {
    if (checkACookieExists(cookieName)) {
        // showCookieModal.value = true;
    }
});
</script>

<template>
    <Flash
        v-show="shouldShowFlash"
        :showCloseButton="false"
        :flash="$page.props.flash"
    ></Flash>

    <teleport to="body">
        <FullScreenSpinner v-if="getIsLoading"></FullScreenSpinner>
    </teleport>
    <slot />
    <CookieConsentModal
        :show="showCookieModal"
        @handleCookieModal="handleCookieModal"
    ></CookieConsentModal>
</template>
