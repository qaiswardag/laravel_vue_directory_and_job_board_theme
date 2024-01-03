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

import { AdjustmentsHorizontalIcon } from "@heroicons/vue/24/outline";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import DefaultElement from "@/Components/Layouts/DefaultElement.vue";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";
import PageHeader from "@/Components/Headers/PageHeader.vue";

defineProps({
    posts: {
        required: false,
    },
});
</script>
<template>
    <Head title="Users" />

    <MainLayout>
        <GuestsLayout>
            <template #header>
                <PageHeader title="Users"></PageHeader>
            </template>
        </GuestsLayout>
    </MainLayout>
</template>
