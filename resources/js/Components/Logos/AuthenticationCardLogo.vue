<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="flex flex-shrink-0 items-center px-4 justify-between">
        <Link
            :href="route('home')"
            class="-m-1.5 p-1.5 myPrimaryParagraph text-3xl sm:text-md font-semibold"
        >
            <span class="sr-only">myself</span>
            myself
        </Link>
    </div>
</template>
