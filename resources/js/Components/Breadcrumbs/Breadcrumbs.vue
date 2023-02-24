<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    links: {
        required: true,
        type: Array,
    },
});
</script>
<template>
    <div class="myBreadcrumbsContainer bg-gray-50">
        <div
            class="myPrimaryParagraph"
            v-for="(link, index) in links"
            :key="index"
        >
            <template v-if="link.url !== undefined">
                <Link
                    :href="route(link.url, link.parameter)"
                    class="myPrimaryLink"
                >
                    {{ link.label }}
                </Link>
            </template>
            <template v-if="link.url === undefined">
                <span class="text-myPrimaryMediumGrayColor">
                    {{ link.label }}
                </span>
            </template>
            <template v-if="index < links.length - 1"> &#47;</template>
        </div>
    </div>
</template>
