<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import DefaultTemplate from "@/Components/PageTemplates/DefaultTemplate.vue";

defineProps({
    post: {
        required: true,
    },
    authors: {
        required: false,
    },
});
</script>
<template>
    <DefaultTemplate :sidebarArea="true">
        <div class="text-center"></div>
        <template #title> {{ post.title }} </template>
        <template #description> Description: {{ post.title }} </template>

        <template #main>
            <div
                v-if="post.cover_image_large !== null"
                class="bg-green-200 rounded-md"
            >
                <img
                    :src="`/storage/uploads/${post.cover_image_large}`"
                    alt=""
                    class="inset-0 -z-10 min-h-[25rem] max-h-[35rem] w-full object-cover rounded-md"
                />
            </div>

            <div class="py-4 my-4">
                <div class="flex flex-wrap gap-2">
                    <template v-for="tag in post.tags.split(',')" :key="tag">
                        <div
                            class="bg-myPrimaryDarkGrayColor px-2 py-1 rounded"
                        >
                            <p class="myPrimaryParagraph text-white">
                                {{ tag }}
                            </p>
                        </div>
                    </template>
                </div>

                <p>
                    Created at:
                    {{ new Date(post.created_at).toLocaleDateString() }}
                </p>
                <p>
                    Updated at:
                    {{ new Date(post.updated_at).toLocaleDateString() }}
                </p>
            </div>
            <p v-html="post.content" class="text-base"></p>

            <p class="mt-16">show author: {{ post.show_author }}</p>
            <p class="my-2">authors is: {{ authors }}</p>
            <p class="my-16">post is: {{ post }}</p>
        </template>

        <template #sidebar>
            <!-- authors details - start -->
            <div v-if="post.show_author === 1 && authors !== null">
                <p class="myPrimaryParagraph">Author:</p>
                <p class="myPrimaryParagraph">
                    {{ authors }}
                </p>
            </div>
            <!-- authors details - end -->

            <!-- team details - start -->
            <div>
                <p>Post team details</p>
            </div>
            <!-- team details - end -->
        </template>
        <template #actions> action area </template>
    </DefaultTemplate>
</template>
