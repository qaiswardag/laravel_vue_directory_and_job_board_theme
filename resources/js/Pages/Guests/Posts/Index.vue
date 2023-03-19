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

            <div class="grid grid-cols-4 myPrimaryGap">
                <div
                    class="p-4 bg-white rounded min-h-[20rem]"
                    v-for="post in posts.data"
                    :key="post.id"
                >
                    <Link
                        :href="route('posts.show', [post.slug_id, post.slug])"
                        class="myTertiaryHeader"
                    >
                        {{ post.title }}
                    </Link>

                    <!-- team details - start -->
                    <div v-if="post.team.length !== 0">
                        <div v-if="post.team.name">
                            <p class="myPrimaryParagraph">Team:</p>
                            <p class="myPrimaryParagraph">
                                {{ post.team.name }}
                            </p>
                        </div>
                        <div v-if="post.team.logo">
                            <img
                                class="inset-0 -z-10 w-16 h-16 object-cover rounded-md"
                                alt="cover image"
                                :src="`/uploads/${post.team.logo}`"
                            />
                        </div>
                    </div>
                    <!-- team details - end -->
                </div>
            </div>
        </div>
        <Pagination :links="posts.links"></Pagination>
    </GuestsLayout>
</template>
