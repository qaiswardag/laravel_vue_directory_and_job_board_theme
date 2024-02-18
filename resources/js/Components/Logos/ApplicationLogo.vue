<script setup>
import { usePage } from "@inertiajs/vue3";

const goToHome = function () {
    if (
        usePage() &&
        usePage().props &&
        usePage().props.environment === "local"
    ) {
        window.location.href = "http://localhost:3000";
    }
    if (
        usePage() &&
        usePage().props &&
        usePage().props.environment !== "local"
    ) {
        window.location.href = "https://www.myself.ae";
    }
};
</script>

<template>
    <img
        @click="goToHome"
        class="lg:w-28 w-24 cursor-pointer"
        src="/logo/myself_text_logo_black.svg"
        alt="Logo"
    />
</template>
