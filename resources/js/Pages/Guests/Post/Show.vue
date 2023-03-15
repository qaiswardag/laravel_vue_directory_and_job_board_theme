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
});
</script>
<template>
    <GuestsLayout :title="post.title">
        <DefaultTemplate :sidebarArea="true">
            <div class="text-center"></div>
            <template #title> {{ post.title }} </template>
            <template #description> Description: {{ post.title }} </template>

            <template #main>
                <div
                    v-if="post.thumbnail !== null"
                    class="bg-green-200 rounded-md"
                >
                    <img
                        :src="`/uploads/${post.thumbnail}`"
                        alt=""
                        class="inset-0 -z-10 min-h-[25rem] max-h-[35rem] w-full object-cover rounded-md"
                    />
                </div>

                <div class="py-4 my-4">
                    <div class="flex flex-wrap gap-2">
                        <template
                            v-for="tag in post.tags.split(',')"
                            :key="tag"
                        >
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
            </template>

            <template #sidebar>
                <div>
                    <div>
                        <p class="myTertiaryHeader">
                            Created by: {{ post.user.first_name }}
                            {{ post.user.last_name }}
                        </p>
                    </div>
                </div>

                <div>
                    <img
                        v-if="post.team.thumbnail"
                        class="inset-0 -z-10 min-h-[10rem] max-h-[15rem] w-full object-cover rounded-md"
                        alt="cover image"
                        :src="`/uploads/${post.team.thumbnail}`"
                    />
                    <p class="myTertiaryHeader">
                        Team:
                        {{ post.team.name }}
                    </p>
                </div>
            </template>
            <template #actions> action area </template>
        </DefaultTemplate>
    </GuestsLayout>
</template>
