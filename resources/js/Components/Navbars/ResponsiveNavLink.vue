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
        ? "bg-red-400 block pl-3 pr-4 py-2 border-l-4 border-myPrimaryColor-400 text-base font-medium text-myPrimaryColor-600 bg-myPrimaryColor-50 focus:outline-none focus:text-myPrimaryColor-600 focus:bg-myPrimaryColor-50 focus:border-myPrimaryColor-600 transition"
        : "block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-myPrimaryNormalColor hover:text-myPrimaryNormalColor hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-myPrimaryNormalColor focus:bg-gray-50 focus:border-gray-300 transition";
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
