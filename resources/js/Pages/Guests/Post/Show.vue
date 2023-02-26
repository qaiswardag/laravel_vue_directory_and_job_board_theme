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
    post: {
        required: true,
    },
});
</script>
<template>
    <GuestsLayout title="Post">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Post</h2>
        </template>
        <div class="myPrimarySection">
            post is: {{ post }}

            <h1 class="mt-12 text-xl">title: {{ post.title }}</h1>
            <p class="mt-12">content: {{ post.content }}</p>
        </div>
    </GuestsLayout>
</template>
