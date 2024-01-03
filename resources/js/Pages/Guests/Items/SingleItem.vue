<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import UnqiueResource from "@/Components/PageTemplates/LoggedIn/Team/UnqiueResource.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
// import { delay } from "@/helpers/";
import { extractTextContentHTML } from "@/helpers/extract-text-content-html";

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
    <Head :title="`${props.postType} | ${post.title}`">
        <meta
            head-key="description"
            name="description"
            :content="extractTextContentHTML(post.content, 155)"
        />
    </Head>

    <MainLayout>
        <GuestsLayout>
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
                    :postType="postType"
                ></UnqiueResource>
            </main>
            <!-- sShow Unique Post - end -->
        </GuestsLayout>
    </MainLayout>
</template>
