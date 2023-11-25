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
    <div class="myBreadcrumbsContainer flex items-center">
        <div
            class="font-medium flex gap-2"
            v-for="(link, index) in links"
            :key="link.label"
        >
            <template v-if="link.route && link.route.name !== undefined">
                <Link
                    :href="route(link.route.name, link.route.parameters)"
                    class="myPrimaryLink flex items-center gap-2"
                >
                    <span>
                        {{ link.label }}
                    </span>
                </Link>
            </template>
            <div v-if="link.label && link.route === undefined">
                <span class="text-myPrimaryMediumGrayColor">
                    {{ link.label }}
                </span>
            </div>
            <template v-if="index < links.length - 1">
                <span class="material-symbols-outlined"> chevron_right </span>
            </template>
        </div>
    </div>
</template>
