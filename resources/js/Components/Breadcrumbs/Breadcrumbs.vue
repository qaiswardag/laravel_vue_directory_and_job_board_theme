<script setup>
import { Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    links: {
        required: true,
        type: Array,
    },
});
</script>
<template>
    <div class="myBreadcrumbsContainer bg-gray-50 font-medium">
        <div
            class="font-medium"
            v-for="(link, index) in links"
            :key="link.label"
        >
            <template v-if="link.route && link.route.name !== undefined">
                <Link
                    :href="route(link.route.name, link.route.parameters)"
                    class="myPrimaryLink"
                >
                    {{ link.label }}
                </Link>
            </template>
            <template v-if="link.label && link.route === undefined">
                <span class="text-myPrimaryMediumGrayColor">
                    {{ link.label }}
                </span>
            </template>
            <template v-if="index < links.length - 1"> &#47;</template>
        </div>
    </div>
</template>
