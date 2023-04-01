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
    <Modal :show="show" @close="firstButton">
        <div
            class="flex flex-row-reverse items-center justify-between py-4 px-4"
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
                Navigation
            </h2>
        </div>
        <slot name="content" />

        <div
            class="relative inline-block align-bottom text-left overflow-hidden transform transition-all sm:align-middle w-full px-4 py-4"
        >
            <slot></slot>
        </div>
    </Modal>
</template>

<style scope>
/*
  Enter and leave animations can use different
  durations and timing functions.
*/
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}

.bounce-enter-active {
    animation: bounce-in 0.5s;
}
.bounce-leave-active {
    animation: bounce-in 0.5s reverse;
}
@keyframes bounce-in {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.25);
    }
    100% {
        transform: scale(1);
    }
}
</style>
