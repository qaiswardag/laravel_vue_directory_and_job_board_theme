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
        class="flex myPrimaryGap items-center overflow-x-scroll bg-gray-100 rounded px-2 pt-3 pb-2"
    >
        <div class="font-medium" v-for="link in links" :key="link.label">
            <Link
                :href="route(link.route.name, link.route.parameters)"
                class="py-2 px-3 my-1 text-xs cursor-pointer font-medium mySecondaryButton gap-2 whitespace-nowrap"
                :class="[
                    route().current(link.route.name)
                        ? 'hover:bg-myPrimaryLinkColor hover:text-white bg-myPrimaryLinkColor text-white'
                        : '',
                ]"
            >
                <template v-if="link.icon === 'work'">
                    <span class="material-symbols-outlined"> work </span>
                </template>
                <template v-if="link.icon === 'draw'">
                    <span class="material-symbols-outlined"> draw </span>
                </template>
                <template v-if="link.icon === 'schedule'">
                    <span class="material-symbols-outlined"> schedule </span>
                </template>
                <template v-if="link.icon === 'delete'">
                    <span class="material-symbols-outlined"> delete </span>
                </template>
                <template v-if="link.icon === 'storefront'">
                    <span class="material-symbols-outlined"> storefront </span>
                </template>
                <template v-if="link.icon === 'hub'">
                    <span class="myMediumIcon material-symbols-outlined">
                        hub
                    </span>
                </template>
                <span>
                    {{ link.label }}
                </span>
            </Link>
        </div>
    </div>
</template>
