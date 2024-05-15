<script setup>
import { usePage } from "@inertiajs/vue3";

const goToHome = function () {
    if (
        usePage() &&
        usePage().props &&
        usePage().props.environment === "local"
    ) {
        window.location.href = "http://localhost:4000";
    }
    if (
        usePage() &&
        usePage().props &&
        usePage().props.environment !== "local"
    ) {
        window.location.href = "https://www.myissue.io";
    }
};
</script>

<template>
    <img
        @click="goToHome"
        class="lg:w-28 w-24 cursor-pointer"
        src="/logo/logo.svg"
        alt="Logo"
    />
    + ØØØ2
</template>
