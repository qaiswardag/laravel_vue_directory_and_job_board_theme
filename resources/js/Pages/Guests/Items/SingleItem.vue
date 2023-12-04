<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import UnqiueResource from "@/Components/PageTemplates/LoggedIn/Team/UnqiueResource.vue";
import MainLayout from "@/Layouts/MainLayout.vue";

const props = defineProps({
    post: {
        required: false,
    },
    team: {
        required: true,
    },
    postType: {
        required: false,
    },
    authors: {
        required: false,
    },
    countries: {
        required: false,
    },
    states: {
        required: false,
    },
    jobTypes: {
        required: false,
    },
    categories: {
        required: false,
    },
    stores: {
        required: false,
    },
});
</script>
<template>
    <MainLayout>
        <GuestsLayout>
            <Head :title="`${props.postType} | ${post.title}`" />
            <!-- Show Unique Resorce - start -->
            <main class="myPrimaryContentSection">
                <UnqiueResource
                    :onlyForCurrentTeam="false"
                    :post="post"
                    :team="team"
                    :authors="authors"
                    :countries="countries"
                    :states="states"
                    :jobTypes="jobTypes"
                    :categories="categories"
                    :stores="stores"
                ></UnqiueResource>
            </main>
            <!-- sShow Unique Post - end -->
        </GuestsLayout>
    </MainLayout>
</template>
