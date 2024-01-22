<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import UnqiueResource from "@/Components/PageTemplates/LoggedIn/Team/UnqiueResource.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
// import { delay } from "@/helpers/";
import { extractTextContentHTML } from "@/helpers/extract-text-content-html";
import { onMounted } from "vue";

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

const currentUrl =
    "https://" + window.location.host + "/storage/uploads/" + "/";
const currentUrlHref = window.location.href;
</script>
<template>
    <Head>
        <title>{{ props.postType }} | {{ post.title }}</title>

        <meta
            name="description"
            head-key="description"
            type="description"
            :content="extractTextContentHTML(post.content, 200)"
        />

        <meta name="keywords" :content="post.tags" />

        <meta head-key="og:type" property="og:type" content="article" />

        <meta property="og:url" :content="currentUrlHref" />

        <meta
            head-key="og:title"
            property="og:title"
            :content="`${props.postType} | ${post.title}`"
        />

        <meta
            name="description"
            head-key="og:description"
            property="og:description"
            :content="extractTextContentHTML(post.content, 200)"
        />

        <template
            v-if="
                Array.isArray(post.cover_images) &&
                post.cover_images.length >= 1
            "
        >
            <meta
                head-key="og:image"
                property="og:image"
                :content="currentUrl + post.cover_images[0].path"
            />
        </template>
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
