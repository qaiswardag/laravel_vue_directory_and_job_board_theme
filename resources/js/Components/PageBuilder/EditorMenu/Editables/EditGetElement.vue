<script setup>
import { useStore } from "vuex";
import { computed, ref } from "vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import TipTapInput from "@/Components/TipTap/TipTapInput.vue";
import PageBuilder from "@/composables/PageBuilder";
import MediaLibraryModal from "@/Components/Modals/MediaLibraryModal.vue";

// store
const store = useStore();
const pageBuilder = new PageBuilder(store);

const getElement = computed(() => {
    return store.getters["pageBuilderState/getElement"];
});

const getShowModalTipTap = computed(() => {
    const result = store.getters["pageBuilderState/getShowModalTipTap"];

    if (result) {
        handleModalPreviewTiptap();
    }
    return result;
});

const getRestoredElement = computed(() => {
    return store.getters["pageBuilderState/getRestoredElement"];
});

const editText = function () {
    console.log(`you cliked edit text!`);
};

// hanlde Tip Tap modal
//
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

const handleModalPreviewTiptap = function () {
    store.commit("pageBuilderState/setShowModalTipTap", true);
    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = "Manage Content";
    descriptionModal.value = null;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = null;

    // handle click
    firstModalButtonFunction.value = function () {
        // set open modal
        store.commit("pageBuilderState/setShowModalTipTap", false);
    };
};

// handle image
// get current image from store
const getBasePrimaryImage = computed(() => {
    return store.getters["pageBuilderState/getBasePrimaryImage"];
});

const isLoading = ref(false);
//
// use media library
const showMediaLibraryModal = ref(false);
// modal content
const titleMedia = ref("");
const descriptionMedia = ref("");
const firstButtonMedia = ref("");
const secondButtonMedia = ref(null);
const thirdButtonMedia = ref(null);
// set dynamic modal handle functions
const firstMediaButtonFunction = ref(null);
const secondMediaButtonFunction = ref(null);
const thirdMediaButtonFunction = ref(null);

const handleAddImage = function () {
    // open modal to true
    showMediaLibraryModal.value = true;

    // set media library modal standards
    titleMedia.value = `Media Library`;
    descriptionMedia.value = null;
    firstButtonMedia.value = "Close";
    secondButtonMedia.value = "Select image";

    // handle click
    firstMediaButtonFunction.value = function () {
        // close media library modal
        showMediaLibraryModal.value = false;
    };
    //
    // handle click
    secondMediaButtonFunction.value = function () {
        isLoading.value = true;
        pageBuilder.updateBasePrimaryImage();
        // close media library modal
        showMediaLibraryModal.value = false;
        isLoading.value = false;
    };
};
</script>
<template>
    <DynamicModal
        :show="getShowModalTipTap"
        maxWidth="5xl"
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
    >
        <header></header>
        <main
            class="lg:min-h-[30rem] lg:max-h-[40rem] md:min-h-[20rem] md:max-h-[30rem] min-h-[20rem] max-h-[20rem] overflow-y-scroll"
        >
            <TipTapInput></TipTapInput>
        </main>
    </DynamicModal>

    <MediaLibraryModal
        :user="$page.props.user"
        :team="$page.props.currentUserTeam"
        :open="showMediaLibraryModal"
        :title="titleMedia"
        :description="descriptionMedia"
        :firstButtonText="firstButtonMedia"
        :secondButtonText="secondButtonMedia"
        :thirdButtonText="thirdButtonMedia"
        @firstMediaButtonFunction="firstMediaButtonFunction"
        @secondMediaButtonFunction="secondMediaButtonFunction"
        @thirdMediaButtonFunction="thirdMediaButtonFunction"
    >
    </MediaLibraryModal>

    <template v-if="getElement || getRestoredElement">
        <div
            class="fixed top-36 left-10 z-20 bg-gray-200 py-1 px-2 rounded shadow"
        >
            <div
                class="flex items-center border border-gray-300 rounded divide-x divide-gray-300 py-1"
            >
                <template v-if="pageBuilder.selectedElementIsValidText()">
                    <div class="px-2">
                        <button
                            @click="handleModalPreviewTiptap"
                            type="button"
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                        >
                            <span class="material-symbols-outlined">
                                edit
                            </span>
                        </button>
                    </div>
                </template>

                <template v-if="getBasePrimaryImage">
                    <div class="px-2">
                        <button
                            @click="handleAddImage"
                            type="button"
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                        >
                            <span class="material-symbols-outlined">
                                add_photo_alternate
                            </span>
                        </button>
                    </div>
                </template>

                <template v-if="true">
                    <div class="px-2">
                        <button
                            @click="
                                store.commit(
                                    'pageBuilderState/setComponent',
                                    null
                                )
                            "
                            type="button"
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                        >
                            <span class="material-symbols-outlined">
                                Close
                            </span>
                        </button>
                    </div>
                </template>

                <!-- delete & restore element # start -->

                <template v-if="getRestoredElement">
                    <div class="px-2">
                        <button
                            @click="pageBuilder.handleRestoreElement"
                            type="button"
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                        >
                            <span class="material-symbols-outlined">
                                refresh
                            </span>
                        </button>
                    </div>
                </template>

                <template v-if="getElement && !getRestoredElement">
                    <div class="px-2">
                        <button
                            @click="pageBuilder.handleDeleteElement"
                            type="button"
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white focus-visible:ring-0 text-myPrimaryErrorColor"
                        >
                            <span
                                class="myMediumIcon material-symbols-outlined"
                            >
                                delete
                            </span>
                        </button>
                    </div>
                </template>
                <!-- delete & restore element # end -->
            </div>
        </div>
    </template>
</template>
