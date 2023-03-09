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
        class="text-myPrimaryDarkGrayColor group flex items-center px-2 py-2 font-medium rounded-md text-base"
        :class="classes"
    >
        <slot />
    </Link>
</template>
