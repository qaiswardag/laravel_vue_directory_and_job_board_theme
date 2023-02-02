<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link :href="'/'">
        <div class="shrink-0 flex items-center">
            <img
                class="h-16 w-auto"
                src="https://tailwindui.com/img/logos/mark.svg?color=black"
                alt="logø"
            />
        </div>
    </Link>
</template>
