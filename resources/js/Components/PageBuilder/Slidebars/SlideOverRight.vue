<script setup>
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { useStore } from "vuex";
import { computed } from "vue";

import {
    XMarkIcon,
    SwatchIcon,
    CubeTransparentIcon,
} from "@heroicons/vue/24/outline";
import { ref, watch } from "vue";

// props
const props = defineProps({
    open: {
        required: true,
    },
    title: {
        required: true,
    },
});

// emit
const emit = defineEmits(["slideOverButton"]);

// button
const slideOverButton = function () {
    emit("slideOverButton");
};
</script>

<template>
    <teleport to="body">
        <TransitionRoot as="template" :show="open">
            <Dialog
                as="div"
                class="relative z-30"
                @click="$emit('slideOverButton')"
            >
                <div class="fixed inset-0" />

                <div class="fixed inset-0 overflow-hidden">
                    <div class="absolute inset-0 overflow-hidden">
                        <div
                            class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 justify-end"
                        >
                            <TransitionChild
                                as="template"
                                enter="transform transition ease-in-out duration-200 sm:duration-200"
                                enter-from="translate-x-full"
                                enter-to="translate-x-0"
                                leave="transform transition ease-in-out duration-200 sm:duration-200"
                                leave-from="translate-x-0"
                                leave-to="translate-x-full"
                            >
                                <DialogPanel
                                    class="pointer-events-auto w-96 max-w-md"
                                >
                                    <div
                                        class="flex h-full flex-col overflow-y-scroll bg-white pt-8 pb-6 shadow-xl"
                                    >
                                        <div class="pl-4 pr-2">
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <DialogTitle
                                                    class="myTertiaryHeader my-0"
                                                >
                                                    {{ title }}
                                                </DialogTitle>
                                                <div
                                                    class="ml-3 flex h-7 items-center"
                                                >
                                                    <button
                                                        @click="
                                                            $emit(
                                                                'slideOverButton'
                                                            )
                                                        "
                                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                    >
                                                        <XMarkIcon
                                                            class="shrink-0 h-4 w-4 m-2 stroke-2"
                                                        >
                                                        </XMarkIcon>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="md:mt-6 mt-4">
                                                <!-- Replace with your content -->
                                                <slot></slot>
                                                <!-- /End replace -->
                                            </div>
                                        </div>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </teleport>
</template>
