<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active ? "text-red-600" : "";
});
</script>

<template>
    <Link
        :href="href"
        class="text-myPrimaryDarkGrayColor group flex items-center px-2 py-4 font-medium rounded-md text-base gap-3"
        :class="classes"
    >
        <slot />
    </Link>
</template>
