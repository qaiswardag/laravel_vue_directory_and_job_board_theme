<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active ? "bg-gray-100" : "";
});
</script>

<template>
    <Link
        :href="href"
        class="text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md"
        :class="classes"
    >
        <slot />
    </Link>
</template>
