<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

defineProps({
    href: {
        String,
    },
    as: {
        String,
    },
    active: {
        required: true,
        Boolean,
    },
});

const classes = computed(() => {
    return props.active
        ? "inline-flex items-center px-1 pt-1 text-sm font-semibold leading-5 focus:outline-none  transition"
        : "inline-flex items-center px-1 pt-1 text-sm font-semibold leading-5 text-myPrimaryNormalColor hover:text-myPrimaryNormalColor focus:outline-none focus:text-myPrimaryNormalColor transition";
});
</script>

<template>
    <div>
        <button
            v-if="as == 'button'"
            type="submit"
            class="border-red-500 block w-full px-4 py-2 text-sm leading-5 text-myPrimaryNormalColor text-left hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition"
            :class="active ? 'bg-gray-100' : ''"
        >
            <slot />
        </button>

        <a
            v-else-if="as == 'a'"
            :href="href"
            class="block px-4 py-2 text-sm leading-5 text-myPrimaryNormalColor hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition"
            :class="active ? 'text-myPrimaryColor-500' : ''"
        >
            <slot />
        </a>

        <Link
            v-else
            :href="href"
            class="block px-4 py-2 text-sm leading-5 text-myPrimaryNormalColor hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition"
            :class="active ? 'text-myPrimaryColor-500' : ''"
        >
            <slot />
        </Link>
    </div>
</template>
