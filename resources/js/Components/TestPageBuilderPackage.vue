<script setup>
import MediaLibraryComponent from "../../../../laravel_vue_directory_and_job_board_theme/ComponentsPageBuilder/CustomMediaLibraryComponent.vue";
import SearchComponent from "../../../../laravel_vue_directory_and_job_board_theme/ComponentsPageBuilder/CustomSearchComponent.vue";
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
        openPageBuilder.value = false;
    };
    secondModalButtonFunction.value = function () {
        openPageBuilder.value = false;
    };
};

// Test data (optional - for future use)
const testUser = {
    id: 1,
    first_name: "Test",
    last_name: "User",
    email: "test@example.com",
};

const testResource = {
    id: 1,
    name: "My Blog Post",
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
                <PageBuilder
                    :CustomMediaLibraryComponent="MediaLibraryComponent"
                    :CustomSearchComponent="SearchComponent"
                    updateOrCreate="create"
                />
            </main>
        </DynamicModal>

        <div class="p-8">
            <h1 class="text-2xl font-bold mb-4">
                Testing External Page Builder Package
            </h1>
            <div class="m-10 bg-red-100 p-4">
                <p>
                    <button
                        class="myPrimaryButton"
                        @click="togglePageBuilder()"
                    >
                        Open page builder
                    </button>
                </p>
            </div>
            <div class="m-10 bg-green-100 p-4 mt-40">
                <PageBuilder
                    :CustomMediaLibraryComponent="MediaLibraryComponent"
                    :CustomSearchComponent="SearchComponent"
                    updateOrCreate="create"
                />
            </div>
        </div>
    </div>
</template>
