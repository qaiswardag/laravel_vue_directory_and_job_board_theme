<script setup>
const props = defineProps({
    links: {
        required: true,
        type: Array,
    },
});
</script>

<template>
    <div class="myBreadcrumbsContainer">
        <div
            class="font-medium"
            v-for="(link, index) in links"
            :key="link.label"
        >
            <Link
                :href="route(link.route.name, link.route.parameters)"
                class="py-2 px-3 my-1 text-xs cursor-pointer font-medium myPrimaryButton whitespace-nowrap"
                :class="[
                    route().current(link.route.name)
                        ? 'bg-red-400'
                        : 'bg-green-400',
                ]"
            >
                {{ link.label }}
            </Link>
        </div>
    </div>
</template>
