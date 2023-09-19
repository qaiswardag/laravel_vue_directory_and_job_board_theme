<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import { CheckIcon, BellIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import PageBuilder from "@/composables/Designer";
import { useStore } from "vuex";

import {
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ref } from "vue";

defineProps({
    show: {
        type: Boolean,
        default: false,
        required: true,
    },
});

// store
const store = useStore();
const pageBuilder = new PageBuilder(store);

const showModalClosePageBuilder = ref(false);
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

const firstButton = function () {
    // set modal standards
    showModalClosePageBuilder.value = true;
    typeModal.value = "danger";
    gridColumnModal.value = 3;
    titleModal.value = "Close the Page Builder without save?";
    descriptionModal.value =
        "Are you sure you want to close the Page Builder without saving? Any changes will be lost.";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Exit Page Builder";

    // handle click
    firstModalButtonFunction.value = function () {
        // set open modal
        showModalClosePageBuilder.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // set open modal
        secondButton();
    };

    // handle click
    thirdModalButtonFunction.value = function () {
        closePageBuilder();
        showModalClosePageBuilder.value = false;
    };
    //
    // end modal
};

const emit = defineEmits([
    "firstDesignerModalButtonFunction",
    "secondDesignerModalButtonFunction",
]);

// first button function
const closePageBuilder = function () {
    pageBuilder.removeHoveredAndSelected();
    emit("firstDesignerModalButtonFunction");
};
// first button function
const secondButton = function () {
    pageBuilder.removeHoveredAndSelected();
    emit("secondDesignerModalButtonFunction");
};
</script>

<template>
    <teleport to="body">
        <TransitionRoot :show="show" as="template">
            <Dialog
                @close="firstButton"
                as="div"
                class="fixed z-30 inset-0 overflow-y-auto"
                tabindex="0"
            >
                <div
                    class="flex items-end justify-center pb-20 text-center sm:block sm:p-0 bg-white"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <DialogOverlay
                            class="fixed inset-0 bg-white bg-opacity-75 transition-opacity"
                        />
                    </TransitionChild>

                    <!-- This element is to trick the browser into centering the modal contents. -->
                    <span
                        aria-hidden="true"
                        class="hidden sm:inline-block sm:align-middle sm:h-screen"
                        >&#8203;</span
                    >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <div
                            class="inline-block align-bottom text-left transform transition-all sm:align-middle w-full overflow-hidden h-[100vh] top-0 left-0 right-0 absolute"
                        >
                            <DynamicModal
                                :show="showModalClosePageBuilder"
                                :type="typeModal"
                                :gridColumnAmount="gridColumnModal"
                                :title="titleModal"
                                :description="descriptionModal"
                                :firstButtonText="firstButtonModal"
                                :secondButtonText="secondButtonModal"
                                :thirdButtonText="thirdButtonModal"
                                @firstModalButtonFunction="
                                    firstModalButtonFunction
                                "
                                @secondModalButtonFunction="
                                    secondModalButtonFunction
                                "
                                @thirdModalButtonFunction="
                                    thirdModalButtonFunction
                                "
                            >
                                <header></header>
                                <main></main>
                            </DynamicModal>
                            <div
                                class="px-6 h-[10vh] flex items-center justify-between"
                            >
                                <button
                                    class="myPrimaryButton"
                                    @click="secondButton"
                                    type="button"
                                >
                                    Save & Close
                                </button>
                                <button
                                    type="button"
                                    @click="firstButton"
                                    class="flex items-center justify-center gap-1 cursor-pointer hover:underline"
                                >
                                    <span
                                        class="myPrimaryParagraph font-medium"
                                    >
                                        Close Builder
                                    </span>
                                    <XMarkIcon
                                        class="h-4 w-4 self-center stroke-2"
                                    ></XMarkIcon>
                                </button>
                            </div>
                            <slot></slot>
                        </div>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>
    </teleport>
</template>
