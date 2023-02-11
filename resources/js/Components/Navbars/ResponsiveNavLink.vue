<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const classes = computed(() => {
    return props.active
        ? "bg-red-400 block pl-3 pr-4 py-2 border-l-4 border-myPrimaryBrandColor text-base font-medium text-myPrimaryBrandColor bg-myPrimaryBrandColor focus:outline-none focus:text-myPrimaryBrandColor focus:bg-myPrimaryBrandColor focus:border-myPrimaryBrandColor transition"
        : "block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-myPrimaryDarkGrayColor hover:text-myPrimaryDarkGrayColor hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-myPrimaryDarkGrayColor focus:bg-gray-50 focus:border-gray-300 transition";
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" :class="classes" class="w-full text-left">
            <slot />
        </button>

        <Link v-else :href="href" :class="classes">
            <slot />
        </Link>
    </div>
</template>
