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
    <Head :title="`${user.first_name} ${user.last_name}`">
        <meta
            head-key="description"
            name="description"
            content="Search for everything you need in the world of fashion within the United Arab Emirates. From fashion stores and jobs to in-store campaigns."
        />
    </Head>

    <MainLayout>
        <GuestsLayout>
            <template #header> </template>

            <div class="myPrimarySection">
                <h1 class="myPrimaryHeader text-center">
                    {{ user.first_name }} {{ user.last_name }}
                </h1>
            </div>
            <div id="page-builder-editor">
                <section v-html="user.content"></section>
            </div>
        </GuestsLayout>
    </MainLayout>
</template>
