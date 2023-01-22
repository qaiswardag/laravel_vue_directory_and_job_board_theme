<template>
    <div>
        <!-- Original Modal - start -->
        <Modal :show="show" :closeable="true" @close="false">
            <slot name="content" />

            <div
                class="relative inline-block align-bottom text-left overflow-hidden transform transition-all sm:align-middle w-full px-4 py-4"
            >
                <div
                    class="flex items-center border-b border-gray-200 pb-2 mb-2"
                >
                    <div class="flex-1">
                        <div v-if="type === 'default'"></div>

                        <div class="flex gap-2 justify-start items-center">
                            <div
                                v-if="type === 'success'"
                                class="flex items-center justify-center h-6 w-6 rounded-full bg-green-100"
                            >
                                <CheckIcon
                                    aria-hidden="true"
                                    class="h-4 w-4 text-green-600"
                                />
                            </div>

                            <div
                                v-if="type === 'warning'"
                                class="flex items-center justify-center h-6 w-6 rounded-full bg-yellow-200"
                            >
                                <BellIcon
                                    aria-hidden="true"
                                    class="h-4 w-4 text-gray-600"
                                />
                            </div>

                            <div
                                v-if="type === 'delete'"
                                class="flex items-center justify-center h-6 w-6 rounded-full bg-red-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-4 h-4 text-red-800"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                    />
                                </svg>
                            </div>

                            <div
                                v-if="type === 'danger'"
                                class="flex items-center justify-center h-6 w-6 rounded-full bg-red-100"
                            >
                                <ExclamationCircleIcon
                                    aria-hidden="true"
                                    class="h-4 w-4 text-red-600"
                                />
                            </div>

                            <h3 as="h3" class="myTertiaryHeader my-0 py-0">
                                {{ title }}
                            </h3>
                        </div>
                    </div>
                    <div @click="firstButton" class="flex-end">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            aria-hidden="true"
                            class="h-6 w-6 text-gray-700 self-center cursor-pointer"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <div
                        v-html="description"
                        class="myPrimaryParagraph mt-4 mb-6"
                    ></div>
                    <header>
                        <slot name="header"></slot>
                    </header>
                    <main>
                        <slot></slot>
                    </main>
                </div>
            </div>

            <div
                class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right"
            >
                <slot name="footer" />
                <div
                    :class="{
                        'sm:grid-cols-1': gridColumnAmount === 1,
                        'sm:grid-cols-2': gridColumnAmount === 2,
                        'sm:grid-cols-3': gridColumnAmount === 3,
                    }"
                    class="sm:grid sm:gap-3 grid gap-4 sm:grid-flow-row-dense md:w-full md:float-right"
                >
                    <div v-if="firstButtonText">
                        <button
                            ref="firstButtonRef"
                            class="myPrimaryButton bg-gray-700 hover:bg-gray-800 text-white focus:ring-gray-700 text-sm w-full"
                            type="button"
                            @click="firstButton"
                        >
                            {{ firstButtonText }}
                        </button>
                    </div>

                    <div v-if="secondButtonText">
                        <button
                            class="myPrimaryButton bg-yellow-300 hover:bg-yellow-400 text-gray-800 hover:text-gray-800 focus:ring-yellow-400 text-sm w-full"
                            type="button"
                            @click="secondButton"
                        >
                            {{ secondButtonText }}
                        </button>
                    </div>

                    <div v-if="thirdButtonText">
                        <div v-if="type === 'default'">
                            <button
                                class="myPrimaryButton bg-emerald-500 focus-visible:ring-emerald-500 focus:ring-emerald-500 hover:bg-emerald-600 text-sm w-full"
                                type="button"
                                @click="thirdButton"
                            >
                                {{ thirdButtonText }}
                            </button>
                        </div>
                        <div v-if="type === 'success'">
                            <button
                                class="myPrimaryButton bg-emerald-500 focus-visible:ring-emerald-500 focus:ring-emerald-500 hover:bg-emerald-600 text-sm w-full"
                                type="button"
                                @click="thirdButton"
                            >
                                {{ thirdButtonText }}
                            </button>
                        </div>

                        <div v-if="type === 'warning'">
                            <button
                                class="myPrimaryButton bg-emerald-500 focus-visible:ring-emerald-500 focus:ring-emerald-500 hover:bg-emerald-600 text-sm w-full"
                                type="button"
                                @click="thirdButton"
                            >
                                {{ thirdButtonText }}
                            </button>
                        </div>

                        <div v-if="type === 'danger' || type === 'delete'">
                            <button
                                class="myPrimaryButton bg-red-500 hover:bg-red-600 text-white focus:ring-red-500 text-sm w-full"
                                type="button"
                                @click="thirdButton"
                            >
                                {{ thirdButtonText }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script>
import Modal from "@/Components/Modals/Modal.vue";
import {
    CheckIcon,
    ExclamationCircleIcon,
    BellIcon,
} from "@heroicons/vue/24/outline";
import {} from "@headlessui/vue";

export default {
    components: {
        CheckIcon,
        ExclamationCircleIcon,
        BellIcon,
        Modal,
    },
    props: {
        show: {
            type: Boolean,
            default: false,
            required: true,
        },
        gridColumnAmount: {
            type: Number,
            required: true,
        },
        type: {
            type: String,
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
        thirdButtonText: {
            type: String,
        },
    },

    setup(props, context) {
        // first button function
        const firstButton = function () {
            context.emit("firstModalButtonFunction");
        };

        // second button  function
        const secondButton = function () {
            context.emit("secondModalButtonFunction");
        };

        // third button function
        const thirdButton = function () {
            context.emit("thirdModalButtonFunction");
        };
        //
        return {
            firstButton,
            secondButton,
            thirdButton,
        };
    },
};
</script>
