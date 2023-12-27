<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import { CheckIcon, BellIcon } from "@heroicons/vue/24/outline";
import ApplicationLogo from "@/Components/Logos/ApplicationLogo.vue";

import {
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";

defineProps({
    show: {
        type: Boolean,
        default: false,
        required: true,
    },
});

const emit = defineEmits(["firstDesignerPreviewModalButtonFunction"]);

// first button function
const firstButton = function () {
    emit("firstDesignerPreviewModalButtonFunction");
};
</script>

<template>
    <teleport to="body">
        <TransitionRoot :show="show" as="template">
            <Dialog
                as="div"
                class="fixed z-30 inset-0 overflow-y-auto"
                @close="firstButton"
                tabindex="0"
            >
                <div
                    class="flex items-end justify-center pb-20 text-center sm:block sm:p-0 bg-white"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-100"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="ease-in duration-100"
                        leave-from="opacity-100"
                        leave-to="opacity-100"
                    >
                        <DialogOverlay
                            class="fixed inset-0 bg-opacity-75 transition-opacity"
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
                        enter="ease-out duration-100"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-100"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <div
                            class="inline-block align-bottom text-left transform transition-all sm:align-middle w-full overflow-hidden h-[100vh] top-0 left-0 right-0 absolute"
                        >
                            <div
                                class="px-4 h-[10vh] flex items-center justify-between border-b border-gray-200 mb-2"
                            >
                                <ApplicationLogo></ApplicationLogo>
                                <div
                                    @click="firstButton"
                                    class="flex items-center justify-center gap-1 cursor-pointer"
                                >
                                    <span
                                        class="myPrimaryParagraph font-medium"
                                    >
                                        Close Preview
                                    </span>
                                    <span class="material-symbols-outlined">
                                        close
                                    </span>
                                </div>
                            </div>
                            <slot></slot>
                        </div>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>
    </teleport>
</template>
