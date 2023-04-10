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
        image: "https://assets.hermes.com/is/image/hermesedito/P_169_WRTW_HEADER_PE22_1?wid=1200",
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
        image: "https://cdn.luxe.digital/media/20220711135910/best-luxury-brands-2022-chanel-luxe-digital.jpg.webp",
        description:
            "Publishing industries for previewing layouts and visual mockups.",
    },
    {
        title: "Unique design and customer loyalty schemes",
        image: "https://hips.hearstapps.com/hmg-prod/images/hbz-dolcegabbana-queens-lori-harvey-1551975747.jpg?crop=1.00xw:0.755xh;0,0.0962xh&resize=1200:*",
        description:
            "Unique design and customer loyalty schemes. Unique design and customer loyalty schemes",
    },
    {
        title: "Chanel remains a privately held company owned",
        image: "https://cdn.mos.cms.futurecdn.net/L7urWTEUAQBuKpPcrQLgzj.jpg",
        description:
            "Chanel remains a privately held company owned by Alain and Gerard Wertheimer, grandsons of Pierre Wertheimer, an early business partner of Coco Chanel.",
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
                                                    class="mySecondaryHeader"
                                                >
                                                    Latest 15 notifications
                                                </DialogTitle>
                                                <div
                                                    class="ml-3 flex h-7 items-center"
                                                >
                                                    <button
                                                        type="button"
                                                        class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50"
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
                                                class="block myPrimaryLink myPrimaryParagraph rounded"
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
                                                            class="text-sm font-semibold text-myPrimaryDarkGrayColor mb-4 mt-2 block"
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
                                                            class="text-xs text-myPrimaryDarkGrayColor my-2"
                                                        >
                                                            Updated: 01.01.2023
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
