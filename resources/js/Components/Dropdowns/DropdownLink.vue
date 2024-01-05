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
        ? "inline-flex items-center px-1 pt-1 text-sm font-normal leading-5 focus:outline-none  transition"
        : "inline-flex items-center px-1 pt-1 text-sm font-normal leading-5 text-myPrimaryDarkGrayColor hover:text-myPrimaryDarkGrayColor focus:outline-none focus:text-myPrimaryDarkGrayColor transition";
});
</script>

<template>
    <div>
        <button
            v-if="as == 'button'"
            type="submit"
            class="border-red-500 block w-full px-4 py-2 text-sm leading-5 text-myPrimaryDarkGrayColor text-left hover:bg-myPrimaryLightGrayColor focus:outline-none focus:bg-myPrimaryLightGrayColor transition"
            :class="active ? 'bg-myPrimaryLightGrayColor' : ''"
        >
            <slot />
        </button>

        <a
            v-else-if="as == 'a'"
            :href="href"
            class="block px-4 py-2 text-sm leading-5 text-myPrimaryDarkGrayColor hover:bg-myPrimaryLightGrayColor focus:outline-none focus:bg-myPrimaryLightGrayColor transition"
            :class="active ? 'text-myPrimaryBrandColor' : ''"
        >
            <slot />
        </a>

        <Link
            v-else
            :href="href"
            class="block px-4 py-2 text-sm leading-5 text-myPrimaryDarkGrayColor hover:bg-myPrimaryLightGrayColor focus:outline-none focus:bg-myPrimaryLightGrayColor transition"
            :class="active ? 'text-myPrimaryBrandColor' : ''"
        >
            <slot />
        </Link>
    </div>
</template>
