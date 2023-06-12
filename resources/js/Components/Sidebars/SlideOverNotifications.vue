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
        title: "Eepteur sint occaecat cupidatat non proident",
        image: "https://images.unsplash.com/photo-1539778100343-71fcce08a31a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80",
        description:
            "Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.",
    },
    {
        title: "Officia deserunt mollit anim id est",
        image: "https://images.unsplash.com/photo-1599950532365-9a9b768229c0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80",
        description:
            "Commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.",
    },
    {
        title: "Duis aute irure",
        description:
            "Publishing industries for previewing layouts and visual mockups.",
    },
    {
        title: "Unique design and customer loyalty schemes",
        image: "https://images.unsplash.com/photo-1468930830753-6699377de411?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80",
        description:
            "Unique design and customer loyalty schemes. Unique design and customer loyalty schemes",
    },
    {
        title: "Lorem remains a privately held company owned",
        image: "https://images.unsplash.com/photo-1561794047-cc8f58725bdd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80",
        description:
            "Privately held company owned by Alain and Gerard Wertheimer, grandsons of Pierre Wertheimer, an early business partner of Coco Chanel.",
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
                                                        class="p-2 hover:bg-myPrimaryLinkColor hover:text-white bg-gray-100 rounded-full cursor-pointer"
                                                        @click="
                                                            $emit(
                                                                'notificationsSlideOverButton'
                                                            )
                                                        "
                                                    >
                                                        <span class="sr-only"
                                                            >Close panel</span
                                                        >
                                                        <XMarkIcon
                                                            class="shrink-0 w-6 h-6"
                                                        ></XMarkIcon>
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
                                                                class="object-cover h-40 w-full xl:h-56 xl:w-full rounded"
                                                                :src="
                                                                    notification.image
                                                                "
                                                            />
                                                        </div>

                                                        <p
                                                            class="text-xs text-myPrimaryDarkGrayColor my-2"
                                                        >
                                                            Updated: 01/01/2023
                                                        </p>
                                                        <p
                                                            class="text-sm text-myPrimaryDarkGrayColor mt-4"
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
