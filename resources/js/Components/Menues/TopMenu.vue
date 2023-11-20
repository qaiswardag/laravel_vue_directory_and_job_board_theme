<script setup>
const props = defineProps({
    links: {
        required: true,
        type: Array,
    },
});
</script>

<template>
    <div
        class="flex myPrimaryGap items-center overflow-x-scroll border border-gray-200 rounded px-2 pt-3 pb-2"
    >
        <div
            class="font-medium"
            v-for="(link, index) in links"
            :key="link.label"
        >
            <Link
                :href="route(link.route.name, link.route.parameters)"
                class="py-2 px-3 my-1 text-xs cursor-pointer font-medium mySecondaryButton whitespace-nowrap"
                :class="[
                    route().current(link.route.name)
                        ? 'hover:bg-myPrimaryLinkColor hover:text-white bg-myPrimaryLinkColor text-white'
                        : '',
                ]"
            >
                {{ link.label }}
            </Link>
        </div>
    </div>
</template>
