<script setup lang="ts">
import MyCustomMediaLibraryComponent from "../../../../laravel_vue_directory_and_job_board_theme/ComponentsPageBuilder/MyCustomMediaLibraryComponent.vue";
import MyCustomSearchComponent from "../../../../laravel_vue_directory_and_job_board_theme/ComponentsPageBuilder/MyCustomSearchComponent.vue";
// Import external package CSS and SHARED STORE
import {
    PageBuilder,
    PageBuilderClass,
    sharedPageBuilderStore,
} from "vue-website-page-builder";
// Import external package CSS
import "vue-website-page-builder/style.css";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import { ref, onMounted } from "vue";

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

const handlePageBuilder = function (e) {
    openPageBuilder.value = true;
    typeModal.value = "default";
    gridColumnModal.value = 2;
    titleModal.value = "Test page builder package";
    descriptionModal.value = null;
    firstButtonModal.value = "Close";
    secondButtonModal.value = "Close page builder";

    // handle click
    firstModalButtonFunction.value = function () {
        console.log("Laravel Dynamic Modal first button – Close FN ran...");
        // openPageBuilder.value = false;
    };
    secondModalButtonFunction.value = function () {
        console.log("Laravel Dynamic Modal second button – Close FN ran...");
        openPageBuilder.value = false;
    };
};

const configPageBuilder = {
    pageBuilderLogo: {
        src: "/logo/logo.svg",
    },
    userForPageBuilder: { name: "John Doe" },
    resourceData: {
        title: "Demo Article",
        id: 1,
    },
    userSettings: {
        theme: "light",
        language: "en",
        autoSave: true,
    },
};

const pageBuilderStateStore = sharedPageBuilderStore;
const pageBuilderClass = new PageBuilderClass(pageBuilderStateStore);
pageBuilderClass.setConfigPageBuilder(configPageBuilder);
</script>

<template>
    <div>
        <section>
            <div>
                <h1>Article Title</h1>
                <p>Article Content</p>
            </div>
        </section>
        <div class="p-6 bg-red-100">
            <h1 class="text-2xl font-bold mb-4">
                Create New Post with Page Builder
            </h1>
            <div class="py-10">
                <p>
                    <button
                        class="myPrimaryButton"
                        @click="handlePageBuilder()"
                    >
                        🚀 Open page builder (with shared store!)
                    </button>
                </p>
            </div>
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
                        :CustomMediaLibraryComponent="
                            MyCustomMediaLibraryComponent
                        "
                        :CustomSearchComponent="MyCustomSearchComponent"
                    />
                </main>
            </DynamicModal>

            <div class="mt-40 py-10 border-t-2 border-black">
                <h1 class="text-2xl font-bold mb-4">
                    Update existing Post with Page Builder
                </h1>
                <PageBuilder
                    :CustomMediaLibraryComponent="MyCustomMediaLibraryComponent"
                    :CustomSearchComponent="MyCustomSearchComponent"
                />
            </div>
        </div>
    </div>
</template>
