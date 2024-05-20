<script setup>
import { usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

const appFrontendUrl = ref(null);

const goToHome = function () {
    if (appFrontendUrl.value) {
        window.location.href = appFrontendUrl.value;
    }
};

onMounted(() => {
    appFrontendUrl.value = import.meta.env.VITE_FRONTEND_APP_URL;
});
</script>

<template>
    <img
        @click="goToHome"
        class="lg:w-28 w-24 cursor-pointer"
        src="/logo/logo.svg"
        alt="Logo"
    />
</template>
