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
import { parseISO, format } from "date-fns";

import { AdjustmentsHorizontalIcon } from "@heroicons/vue/24/outline";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import DefaultElement from "@/Components/Layouts/DefaultElement.vue";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";
import TemplateListResources from "@/Pages/Guests/TemplateListResources/TemplateListResources.vue";
</script>
<template>
    <MainLayout>
        <GuestsLayout>
            <Head title="Blog" />
            <template #header>
                <div class="myPrimaryMainPageHeaderParagraph">
                    <h1 class="myPrimaryMainPageHeaderNotLoggedIn">Posts</h1>
                    <p class="myPrimaryMainPageParagraphNotLoggedIn">
                        Discover the ultimate minimalist administration panel
                        with robust and multiple security layers and advanced
                        features like Team Management, Blog and Media Library.
                    </p>
                </div>
            </template>
        </GuestsLayout>
    </MainLayout>
</template>
