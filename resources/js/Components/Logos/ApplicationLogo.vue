<script setup>
import { router } from "@inertiajs/vue3";
</script>

<template>
    <Link :href="route('home')" class="myPrimaryParagraph">
        <img class="h-6" src="/logo/logo-myissue.svg" alt="Logo" />
    </Link>
</template>
