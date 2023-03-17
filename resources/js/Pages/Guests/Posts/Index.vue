<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import SearchBarWithOptions from "@/Components/SearchBars/SearchBarWithOptions.vue";
import { onMounted, ref } from "vue";

defineProps({
    posts: {
        required: true,
    },
});
</script>
<template>
    <GuestsLayout title="Posts">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Posts</h2>
        </template>
        <div class="myPrimarySection">
            <h1 class="my-8 text-4xl text-center">Posts</h1>

            <div v-for="post in posts.data" :key="post.id">
                <div class="my-12 mx-24 border-2 border-red-400 p-4">
                    <Link
                        :href="route('posts.show', [post.slug_id, post.slug])"
                        class="myPrimaryLink text-2xl font-semibold mt-4"
                    >
                        {{ post.title }}
                    </Link>
                    <p class="my-8">
                        slug:
                        <span class="text-red-600">
                            {{ post.slug }}
                        </span>
                    </p>

                    <p class="my-8 text-xl">
                        Publish Status:
                        <span class="text-purple-600 font-semibold">
                            {{ post.published ? "Published" : "Private" }}
                        </span>
                    </p>
                    <p class="my-4">post id is: {{ post.id }}</p>
                </div>
            </div>
        </div>
        <Pagination :links="posts.links"></Pagination>
    </GuestsLayout>
</template>
