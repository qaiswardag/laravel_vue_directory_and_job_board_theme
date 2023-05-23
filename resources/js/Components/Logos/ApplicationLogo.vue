<script setup>
import { router } from "@inertiajs/vue3";
</script>

<template>
    <Link
        :href="route('home')"
        class="-m-1.5 p-1.5 myPrimaryParagraph text-3xl sm:text-md font-semibold"
    >
        <span class="sr-only">myself</span>
        myself
    </Link>
</template>
