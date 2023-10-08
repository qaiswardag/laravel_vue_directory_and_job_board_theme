<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import SearchBarWithOptions from "@/Components/SearchBars/SearchBarWithOptions.vue";
import { onMounted, ref } from "vue";

defineProps({
    user: {
        required: true,
    },
});
</script>
<template>
    <MainLayout>
        <GuestsLayout>
            <template #header> </template>
            <div class="myPrimarySection">
                <p class="my-12">User: {{ JSON.stringify(user) }}</p>
                <p class="text-3xl my-8">
                    {{ user.first_name }}
                    {{ user.last_name }}
                </p>
            </div>
        </GuestsLayout>
    </MainLayout>
</template>
