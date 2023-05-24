<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? "inline-flex items-center px-1 pt-1 text-sm font-normal leading-5 focus:outline-none  transition"
        : "inline-flex items-center px-1 pt-1 text-sm font-normal leading-5 text-myPrimaryDarkGrayColor hover:text-myPrimaryDarkGrayColor focus:outline-none focus:text-myPrimaryDarkGrayColor transition";
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
