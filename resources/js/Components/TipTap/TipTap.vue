<script setup>
import { computed, onBeforeMount, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
import PageBuilder from "@/composables/PageBuilder";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import TipTapInput from "@/Components/TipTap/TipTapInput.vue";

const store = useStore();

const pageBuilder = new PageBuilder(store);

const toggleTipTap = ref(true);

const getShowModalTipTap = computed(() => {
    const result = store.getters["pageBuilderState/getShowModalTipTap"];

    if (result) {
        handleModalPreviewTiptap();
    }
    return result;
});

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
</script>
<template>
    <div>
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

        <div
            class="mt-2 mb-10 blockease-linear duration-200 block px-2 ease-linear"
        >
            <template v-if="pageBuilder.clickOnTextElement()">
                <div
                    class="h-14 px-2 bg-gray-100 rounded-full sticky top-0 z-20 flex gap-4 flex-shrink-0 justify-start items-center border-gray-100 shadow"
                >
                    <button
                        @click="
                            store.commit(
                                'pageBuilderState/setShowModalTipTap',
                                true
                            )
                        "
                        type="button"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                    >
                        <span class="material-symbols-outlined">
                            open_in_full
                        </span>
                    </button>
                    <button
                        @click="toggleTipTap = !toggleTipTap"
                        type="button"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                    >
                        <span
                            v-if="toggleTipTap"
                            class="material-symbols-outlined"
                        >
                            collapse_content
                        </span>
                        <span
                            v-if="!toggleTipTap"
                            class="material-symbols-outlined"
                        >
                            expand_content
                        </span>
                    </button>
                </div>
            </template>
            <TipTapInput v-if="!getShowModalTipTap && toggleTipTap">
            </TipTapInput>
        </div>
    </div>
</template>
