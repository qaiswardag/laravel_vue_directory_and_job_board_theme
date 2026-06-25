<script setup>
import { onMounted } from "vue";
import { PageBuilder, getPageBuilder } from "vue-website-page-builder";

const props = defineProps({
    currentUserTeamRole: {
        required: false,
    },
    currentUserTeam: {
        required: true,
    },
    user: {
        required: true,
    },
    post: {
        default: null,
        required: false,
    },
    postAuthor: {
        default: null,
        required: false,
    },
    categories: {
        default: null,
        required: false,
    },
    states: {
        default: null,
        required: false,
    },
    coverImages: {
        default: null,
        required: false,
    },
    brandLogos: {
        default: null,
        required: false,
    },
});

const configPageBuilder = {
    updateOrCreate: {
        formType: "update",
        formName: "article",
    },
    userSettings: {
        theme: "light",
        language: {
            default: "zh-Hans",
            enable: ["en", "zh-Hans", "fr"],
        },
        autoSave: true,
    },
};

const pageBuilderService = getPageBuilder();

// Initialize the Page Builder with `onMounted`
onMounted(async () => {
    const result = await pageBuilderService.startBuilder(configPageBuilder);
    console.info(
        "You may inspect this result for message, status, or error:",
        result
    );
});
</script>

<template>
    <PageBuilder />
</template>
