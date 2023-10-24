<script setup>
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import UnqiueResource from "@/Components/PageTemplates/LoggedIn/Team/UnqiueResource.vue";
import MainLayout from "@/Layouts/MainLayout.vue";

// const props = defineProps({
//     post: {
//         required: true,
//     },
//     authors: {
//         required: true,
//     },
//     currentUserTeam: {
//         required: true,
//     },
//     categories: {
//         required: true,
//     },
//     states: {
//         required: true,
//     },
//     coverImages: {
//         required: true,
//     },
// });
const props = defineProps({
    post: {
        required: false,
    },
    authors: {
        required: false,
    },
    currentUserTeam: {
        required: false,
    },
    categories: {
        required: false,
    },
    states: {
        required: false,
    },
    coverImages: {
        required: false,
    },
});
</script>
<template>
    <MainLayout>
        <LoggedInLayout>
            <Head :title="post.title" />
            <template #header>
                <h2 class="myPrimaryMainPageHeader">
                    ØØØØØØØØØØØØØØØØØØØØØØØØØØØØØØØØ
                </h2>
            </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <!-- Show Unique Resorce - start -->
            <UnqiueResource
                :post="post"
                :authors="authors"
                :categories="categories"
                :states="states"
                :coverImages="coverImages"
            ></UnqiueResource>
            <!-- sShow Unique Post - end -->
        </LoggedInLayout>
    </MainLayout>
</template>
