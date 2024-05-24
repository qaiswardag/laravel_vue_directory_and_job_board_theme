<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active ? "bg-myPrimaryLightGrayColor" : "";
});
</script>

<template>
    <Link
        :href="href"
        class="text-myPrimaryDarkGrayColor group flex items-center px-4 py-4 font-medium text-base gap-3 rounded-full hover:bg-myPrimaryLightGrayColor"
        :class="classes"
    >
        <slot />
    </Link>
</template>
