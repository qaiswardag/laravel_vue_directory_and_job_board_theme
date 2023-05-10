<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import {
    CheckIcon,
    ExclamationCircleIcon,
    BellIcon,
} from "@heroicons/vue/24/outline";
import {} from "@headlessui/vue";

defineProps({
    show: {
        type: Boolean,
        default: false,
        required: true,
    },

    title: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(["firstModalMenuButtonFunction"]);

// first button function
const firstButton = function () {
    emit("firstModalMenuButtonFunction");
};
</script>

<template>
    <Modal :show="show" @close="firstButton" maxWidth="md">
        <div
            class="px-4 mb-4 flex flex-row-reverse items-center justify-between"
        >
            <button
                @click="firstButton"
                aria-label="Close menu"
                class="-m-1 p-1"
                type="button"
                data-headlessui-state="open"
                tabindex="0"
            >
                <svg
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                    class="h-6 w-6 text-zinc-500 dark:text-zinc-400"
                >
                    <path
                        d="m17.25 6.75-10.5 10.5M6.75 6.75l10.5 10.5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    ></path>
                </svg>
            </button>
            <h2 class="text-sm font-medium text-zinc-600 dark:text-zinc-400">
                {{ title }}
            </h2>
        </div>
        <div
            class="px-4 relative inline-block align-bottom text-left overflow-hidden transform transition-all sm:align-middle w-full"
        >
            <slot name="content" />

            <slot></slot>
        </div>
    </Modal>
</template>
