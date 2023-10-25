<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
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
        <GuestsLayout>
            <Head :title="post.title" />
            <!-- Show Unique Resorce - start -->
            <main class="myPrimaryContentSection">
                <UnqiueResource
                    :post="post"
                    :authors="authors"
                    :categories="categories"
                    :states="states"
                    :coverImages="coverImages"
                ></UnqiueResource>
            </main>
            <!-- sShow Unique Post - end -->
        </GuestsLayout>
    </MainLayout>
</template>
