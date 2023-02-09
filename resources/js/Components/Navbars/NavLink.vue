<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? "inline-flex items-center px-1 pt-1 text-sm font-semibold leading-5 focus:outline-none  transition"
        : "inline-flex items-center px-1 pt-1 text-sm font-semibold leading-5 text-myPrimaryGrayColor hover:text-myPrimaryGrayColor focus:outline-none focus:text-myPrimaryGrayColor transition";
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
