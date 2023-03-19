<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import { ref } from "vue";

import {} from "@headlessui/vue";

defineProps({
    team: {
        required: true,
    },
    user: {
        required: true,
    },
    show: {
        type: Boolean,
        default: false,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        required: true,
    },
    firstButtonText: {
        type: String,
    },
    secondButtonText: {
        type: String,
    },
});

const emit = defineEmits([
    "firstModalButtonSearchAuthorFunction",
    "secondModalButtonSearchAuthorFunction",
]);

// first button function
const firstButton = function () {
    emit("firstModalButtonSearchAuthorFunction");
};
// second button  function
const secondButton = function () {
    emit("secondModalButtonSearchAuthorFunction");
};

const searchCategories = ref(["name", "email"]);
</script>

<template>
    <Modal :show="show" @close="firstButton">
        <div
            class="relative inline-block align-bottom text-left overflow-hidden transform transition-all sm:align-middle w-full px-4 py-4"
        >
            <div
                class="flex items-center border-b border-gray-200 pb-2 mb-2 justify-between"
            >
                <h3 class="tertiaryHeader my-0 py-0">
                    {{ title }}
                </h3>
                <div @click="firstButton" class="flex-end">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        aria-hidden="true"
                        class="h-6 w-6 text-myPrimaryDarkGrayColor self-center cursor-pointer"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </div>
            </div>
            <!-- content here -->
        </div>

        <div class="flex flex-col px-6 py-4 bg-gray-100 text-right">
            <div
                class="sm:grid sm:gap-3 sm:grid-cols-2 grid gap-4 sm:grid-flow-row-dense md:w-full"
            >
                <button
                    v-if="firstButtonText"
                    ref="firstButtonRef"
                    class="shadow myPrimaryButton bg-myPrimaryMediumGrayColor text-sm w-full hover:bg-gray-500 text-white hover:text-white focus:ring-gray-500"
                    type="button"
                    @click="firstButton"
                >
                    {{ firstButtonText }}
                </button>

                <div v-if="secondButtonText">
                    <div>
                        <button
                            class="flex items-center gap-2 myPrimaryButton text-sm w-full"
                            type="button"
                            @click="secondButton"
                        >
                            {{ secondButtonText }}
                        </button>
                    </div>
                </div>
            </div>
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
