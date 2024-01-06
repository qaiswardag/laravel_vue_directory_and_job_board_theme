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
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";

defineProps({
    userData: {
        required: true,
    },
});
</script>
<template>
    <Head :title="`${userData.first_name} ${userData.last_name}`">
        <meta
            head-key="description"
            name="description"
            :content="`User at myself.ae — ${userData.first_name} ${userData.last_name}`"
        />
    </Head>

    <MainLayout>
        <GuestsLayout>
            <template #header> </template>
            <div class="myPrimarySection">
                <div
                    v-if="
                        userData.user_photo &&
                        Array.isArray(userData.user_photo) &&
                        userData.user_photo.length !== 0
                    "
                    class="flex justify-center items-center w-full mb-8"
                >
                    <ThumbnailSmallImageSlider
                        :images="userData.user_photo"
                        imageSize="large_path"
                        imageHeight="h-32"
                        imageWidth="w-32"
                        :roundedFull="true"
                    ></ThumbnailSmallImageSlider>
                </div>

                <h1 class="myPrimaryHeader text-center">
                    {{ userData.first_name }} {{ userData.last_name }}
                </h1>
            </div>
            <div id="page-builder-editor">
                <section v-html="userData.content"></section>
            </div>
        </GuestsLayout>
    </MainLayout>
</template>
