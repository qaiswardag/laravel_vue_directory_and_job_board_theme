<script setup>
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { XMarkIcon } from "@heroicons/vue/24/outline";

// props
const props = defineProps({
    open: {
        required: true,
    },
});

// notifications
const notifications = [
    {
        title: "New platform",
        image: "/app-images/pages/home/frequently_asked_questions_2.jpg",
        description:
            "We're thrilled to announce the launch of our new platform, complete with exciting features.",
    },
];
// emit
const emit = defineEmits(["notificationsSlideOverButton"]);

// button
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
                class="relative z-40"
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
                                        class="flex h-full flex-col overflow-y-scroll bg-white pt-3 pb-6 shadow-xl"
                                    >
                                        <div class="px-4 sm:px-6">
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <DialogTitle
                                                    class="myPrimaryParagraph text-base rounded w-full py-4 font-medium"
                                                >
                                                    Latest notifications
                                                </DialogTitle>
                                                <div
                                                    class="ml-3 flex h-7 items-center"
                                                >
                                                    <button
                                                        type="button"
                                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                        @click="
                                                            $emit(
                                                                'notificationsSlideOverButton'
                                                            )
                                                        "
                                                    >
                                                        <span class="sr-only"
                                                            >Close panel</span
                                                        >
                                                        <span
                                                            class="material-symbols-outlined"
                                                        >
                                                            close
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
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
                                                        <p
                                                            class="text-sm font-medium text-myPrimaryDarkGrayColor mb-4 mt-2 block"
                                                        >
                                                            {{
                                                                notification.title
                                                            }}
                                                        </p>
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
                                                            <img
                                                                alt="notification thumbnail"
                                                                class="object-cover h-40 w-full xl:h-56 xl:w-full rounded object-bottom"
                                                                :src="
                                                                    notification.image
                                                                "
                                                            />
                                                        </div>

                                                        <p
                                                            class="text-sm text-myPrimaryDarkGrayColor mt-4"
                                                        >
                                                            {{
                                                                notification.description.slice(
                                                                    0,
                                                                    120
                                                                )
                                                            }}
                                                        </p>

                                                        <div
                                                            class="flex items-center justify-left gap-2"
                                                        ></div>
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
