<script setup>
import CustomMediaLibraryComponent from "../../../../laravel_vue_directory_and_job_board_theme/ComponentsPageBuilder/CustomMediaLibraryComponent.vue";
import CustomSearchComponent from "../../../../laravel_vue_directory_and_job_board_theme/ComponentsPageBuilder/CustomSearchComponent.vue";
// Import external package CSS
import { PageBuilder } from "vue-website-page-builder";
// Import external package CSS
import "vue-website-page-builder/style.css";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import { ref } from "vue";

const openPageBuilder = ref(false);
// use dynamic model
const typeModal = ref("");
const gridColumnModal = ref(Number(1));
const titleModal = ref("");
const descriptionModal = ref("");
const firstButtonModal = ref("");
const secondButtonModal = ref(null);
const thirdButtonModal = ref(null);
// set dynamic modal handle functions
const firstModalButtonFunction = ref(null);
const secondModalButtonFunction = ref(null);
const thirdModalButtonFunction = ref(null);

const togglePageBuilder = function (e) {
    openPageBuilder.value = true;
    typeModal.value = "default";
    gridColumnModal.value = 2;
    titleModal.value = "Test page builder package";
    descriptionModal.value = null;
    firstButtonModal.value = "Close";
    secondButtonModal.value = "Close page builder";

    // handle click
    firstModalButtonFunction.value = function () {
        // openPageBuilder.value = false;
    };
    secondModalButtonFunction.value = function () {
        openPageBuilder.value = false;
    };
};
</script>

<template>
    <div>
        <DynamicModal
            :show="openPageBuilder"
            :type="typeModal"
            :gridColumnAmount="gridColumnModal"
            :title="titleModal"
            :description="descriptionModal"
            :firstButtonText="firstButtonModal"
            :secondButtonText="secondButtonModal"
            :thirdButtonText="thirdButtonModal"
            @firstModalButtonFunction="firstModalButtonFunction"
            @secondModalButtonFunction="secondModalButtonFunction"
            @thirdModalButtonFunction="thirdModalButtonFunction"
            maxWidth="max-2xl"
        >
            <header></header>
            <main>
                <PageBuilder />
            </main>
        </DynamicModal>

        <div class="p-8">
            <h1 class="text-2xl font-bold mb-4">
                Testing External Page Builder Package in Modal
            </h1>
            <div class="bg-red-100 py-10">
                <p>
                    <button
                        class="myPrimaryButton"
                        @click="togglePageBuilder()"
                    >
                        Open page builder
                    </button>
                </p>
            </div>
            <div class="bg-green-100 mt-40 py-10">
                <h1 class="text-2xl font-bold mb-4">
                    Testing External Page Builder Package
                </h1>
                <PageBuilder
                    :CustomMediaLibraryComponent="CustomMediaLibraryComponent"
                    :CustomSearchComponent="CustomSearchComponent"
                    updateOrCreate="update"
                    :userForPageBuilder="{ name: 'John Doe' }"
                    :resourceData="{ title: 'Demo Article', id: 1 }"
                />
            </div>
        </div>
    </div>
</template>
