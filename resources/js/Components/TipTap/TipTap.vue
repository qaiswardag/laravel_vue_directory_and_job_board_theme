<script setup>
import { computed, onBeforeMount, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
import PageBuilder from "@/composables/PageBuilder";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import TipTapInput from "@/Components/TipTap/TipTapInput.vue";

const showModalTipTap = ref(false);
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
    showModalTipTap.value = true;
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
        showModalTipTap.value = false;
    };
};
</script>
<template>
    <div>
        <DynamicModal
            :show="showModalTipTap"
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
            <main>
                <TipTapInput></TipTapInput>
            </main>
        </DynamicModal>

        <div
            class="mt-2 mb-10 blockease-linear duration-200 block px-2 ease-linear"
        >
            <div class="flex items-center gap-2 my-2 bg-transparent py-2">
                <button
                    @click="handleModalPreviewTiptap"
                    type="button"
                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                >
                    <span class="material-symbols-outlined">
                        pinch_zoom_out
                    </span>
                </button>
            </div>
            <TipTapInput v-if="!showModalTipTap"> </TipTapInput>
        </div>
    </div>
</template>
