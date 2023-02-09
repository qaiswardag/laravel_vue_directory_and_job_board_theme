<script setup>
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";

// props
const props = defineProps({
    open: {
        required: true,
    },
});

// notifications
const notifications = [
    {
        title: "Eepteur sint occaecat cupidatat non proident",
        image: "https://images.unsplash.com/photo-1674507888194-85b1859fc034?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1974&q=80",
        description:
            "Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.",
    },
    {
        title: "Officia deserunt mollit anim id est",
        description:
            "Commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.",
    },
    {
        title: "Duis aute irure",
        image: "https://images.unsplash.com/photo-1661956602116-aa6865609028?ixlib=rb-4.0.3&ixid=MnwxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1964&q=80",
        description:
            "Publishing industries for previewing layouts and visual mockups.",
    },
];
// emit
const emit = defineEmits(["notificationsSlideOverButton"]);

// search anything modal button
const notificationsSlideOverButton = function () {
    emit("notificationsSlideOverButton");
};

// setup code
</script>

<template>
    <teleport to="body">
        <TransitionRoot as="template" :show="open">
            <Dialog
                as="div"
                class="relative z-10"
                @click="$emit('notificationsSlideOverButton')"
            >
                <div class="fixed inset-0" />

                <div class="fixed inset-0 overflow-hidden">
                    <div class="absolute inset-0 overflow-hidden">
                        <div
                            class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10"
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
                                    class="pointer-events-auto w-screen max-w-md"
                                >
                                    <div
                                        class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl"
                                    >
                                        <div class="px-4 sm:px-6">
                                            <div
                                                class="flex items-start justify-between"
                                            >
                                                <DialogTitle
                                                    class="mySecondaryHeader"
                                                >
                                                    Latest 15 notifications
                                                </DialogTitle>
                                                <div
                                                    class="ml-3 flex h-7 items-center"
                                                >
                                                    <button
                                                        type="button"
                                                        class="rounded-md bg-white text-myPrimaryBrandColor hover:text-myPrimaryBrandColor focus:outline-none focus:ring-2 focus:ring-myPrimaryBrandColor focus:ring-offset-2"
                                                        @click="
                                                            $emit(
                                                                'notificationsSlideOverButton'
                                                            )
                                                        "
                                                    >
                                                        <span class="sr-only"
                                                            >Close panel</span
                                                        >

                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
                                                            class="w-6 h-6"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12"
                                                            />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <a
                                                href="#"
                                                class="block myPrimaryLink primaryParagraph rounded"
                                            >
                                                View all notifications
                                            </a>
                                        </div>
                                        <div
                                            class="relative mt-0 flex-1 px-4 sm:px-6"
                                        >
                                            <!-- Replace with your content -->
                                            <div
                                                class="absolute inset-0 px-4 sm:px-6"
                                            >
                                                <div
                                                    v-if="
                                                        notifications.length > 0
                                                    "
                                                    class="grid grid-cols-1 myPrimaryGap mt-4"
                                                >
                                                    <div
                                                        v-for="notification in notifications"
                                                        :key="notification.id"
                                                        class="border border-gray-200 bg-gray-50 py-4 px-4 rounded"
                                                    >
                                                        <a
                                                            href="#"
                                                            class="text-sm font-medium text-myPrimaryGrayColor mb-4 mt-2 block"
                                                        >
                                                            {{
                                                                notification.title
                                                            }}
                                                        </a>
                                                        <div
                                                            v-if="
                                                                notification.image ===
                                                                null
                                                            "
                                                        ></div>
                                                        <div
                                                            v-if="
                                                                notification.image
                                                            "
                                                        >
                                                            <a href="#">
                                                                <img
                                                                    alt="notification thumbnail"
                                                                    class="object-cover h-40 w-full xl:h-56 xl:w-full rounded"
                                                                    :src="
                                                                        notification.image
                                                                    "
                                                                />
                                                            </a>
                                                        </div>

                                                        <p
                                                            class="text-xs text-myPrimaryGrayColor my-2"
                                                        >
                                                            Updated: 01.01.2023
                                                        </p>
                                                        <p
                                                            class="text-sm text-myPrimaryGrayColor mt-4"
                                                        >
                                                            {{
                                                                notification.description.slice(
                                                                    0,
                                                                    120
                                                                )
                                                            }}...
                                                        </p>

                                                        <div
                                                            class="flex items-center justify-left gap-2"
                                                        >
                                                            <PrimaryButton
                                                                class="mt-4 mb-2 text-xs"
                                                                :class="{
                                                                    'opacity-25': false,
                                                                }"
                                                                :disabled="
                                                                    false
                                                                "
                                                            >
                                                                Read more
                                                            </PrimaryButton>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /End replace -->
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
