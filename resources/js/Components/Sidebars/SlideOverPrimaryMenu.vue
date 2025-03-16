<script setup>
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronRightIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import { usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

// props
const props = defineProps({
    open: {
        required: false,
    },
});

const emit = defineEmits(["primaryMenuSlideOverButton"]);

// button
const primaryMenuSlideOverButton = function () {
    emit("primaryMenuSlideOverButton");
};

const navigation = [
    {
        label: "Resources",
        route: {
            name: null,
            parameters: [],
        },
        active: route().current("terms.show") || route().current("policy.show"),
        children: [
            {
                label: "Terms of Service",
                route: {
                    name: "terms.show",
                    parameters: [],
                },
                active: route().current("terms.show"),
            },
            {
                label: "Privacy Policy",
                route: {
                    name: "policy.show",
                    parameters: [],
                },
                active: route().current("policy.show"),
            },
        ],
    },
];

const appFrontendUrl = ref(null);

const goToStores = function () {
    if (appFrontendUrl.value) {
        window.location.href = appFrontendUrl.value + "/listings";
    }
};
const goToJobs = function () {
    if (appFrontendUrl.value) {
        window.location.href = appFrontendUrl.value + "/jobs";
    }
};
const goToPosts = function () {
    if (appFrontendUrl.value) {
        window.location.href = appFrontendUrl.value + "/posts";
    }
};
const goProfessional = function () {
    if (appFrontendUrl.value) {
        window.location.href = appFrontendUrl.value + "/professional";
    }
};

onMounted(() => {
    appFrontendUrl.value = import.meta.env.VITE_FRONTEND_APP_URL;
});
</script>

<template>
    <teleport to="body">
        <TransitionRoot as="template" :show="open">
            <Dialog
                as="div"
                class="relative z-40"
                @click="$emit('primaryMenuSlideOverButton')"
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
                                                class="flex items-center justify-between mb-4"
                                            >
                                                <DialogTitle
                                                    class="myPrimaryParagraph text-base rounded w-full pt-4 font-medium"
                                                >
                                                    Main Menu
                                                </DialogTitle>
                                                <div
                                                    class="ml-3 flex h-7 items-center"
                                                >
                                                    <button
                                                        type="button"
                                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                        @click="
                                                            $emit(
                                                                'primaryMenuSlideOverButton'
                                                            )
                                                        "
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
                                            <!-- Replace with content -->
                                            <nav class="flex flex-1 flex-col">
                                                <ul
                                                    role="list"
                                                    class="flex flex-1 flex-col gap-y-2"
                                                >
                                                    <li
                                                        class="block w-full hover:bg-myPrimaryLightGrayColor rounded-md text-left"
                                                    >
                                                        <button
                                                            @click="goToStores"
                                                            type="button"
                                                            class="block w-full py-3 pl-3 pr-1 hover:bg-myPrimaryLightGrayColor rounded-md text-left"
                                                        >
                                                            Listings
                                                        </button>
                                                    </li>
                                                    <li
                                                        class="block w-full hover:bg-myPrimaryLightGrayColor rounded-md text-left"
                                                    >
                                                        <button
                                                            @click="goToJobs"
                                                            type="button"
                                                            class="block w-full py-3 pl-3 pr-1 hover:bg-myPrimaryLightGrayColor rounded-md text-left"
                                                        >
                                                            Jobs
                                                        </button>
                                                    </li>
                                                    <li
                                                        class="block w-full hover:bg-myPrimaryLightGrayColor rounded-md text-left"
                                                    >
                                                        <button
                                                            @click="goToPosts"
                                                            type="button"
                                                            class="block w-full py-3 pl-3 pr-1 hover:bg-myPrimaryLightGrayColor rounded-md text-left"
                                                        >
                                                            Posts
                                                        </button>
                                                    </li>
                                                    <li
                                                        class="block w-full hover:bg-myPrimaryLightGrayColor rounded-md text-left"
                                                    >
                                                        <button
                                                            @click="
                                                                goProfessional
                                                            "
                                                            type="button"
                                                            class="block w-full py-3 pl-3 pr-1 hover:bg-myPrimaryLightGrayColor rounded-md text-left"
                                                        >
                                                            Professional
                                                        </button>
                                                    </li>
                                                    <li
                                                        class="block w-full hover:bg-myPrimaryLightGrayColor rounded-md text-left"
                                                    >
                                                        <button
                                                            @click="
                                                                goProfessional
                                                            "
                                                            type="button"
                                                            class="block w-full py-3 pl-3 pr-1 hover:bg-myPrimaryLightGrayColor rounded-md text-left"
                                                        >
                                                            Contact us
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <ul
                                                            role="list"
                                                            class="space-y-2"
                                                        >
                                                            <li
                                                                v-for="item in navigation"
                                                                :key="
                                                                    item.label
                                                                "
                                                            >
                                                                <Link
                                                                    class="block w-full py-3 pl-3 pr-1 hover:bg-myPrimaryLightGrayColor rounded-md text-left"
                                                                    :class="[
                                                                        item.active
                                                                            ? 'text-myPrimaryLinkColor'
                                                                            : '',
                                                                    ]"
                                                                    v-if="
                                                                        !item.children
                                                                    "
                                                                    :href="
                                                                        route(
                                                                            item
                                                                                .route
                                                                                .name,
                                                                            item
                                                                                .route
                                                                                .parameters
                                                                        )
                                                                    "
                                                                    >{{
                                                                        item.label
                                                                    }}
                                                                </Link>

                                                                <Disclosure
                                                                    as="div"
                                                                    v-if="
                                                                        item.children
                                                                    "
                                                                    v-slot="{
                                                                        open,
                                                                    }"
                                                                >
                                                                    <DisclosureButton
                                                                        :class="[
                                                                            item.active
                                                                                ? 'hover:bg-myPrimaryLightGrayColor'
                                                                                : '',
                                                                            'flex items-center justify-between w-full text-left gap-x-3 py-3 pl-3 pr-1 rounded-md',
                                                                        ]"
                                                                    >
                                                                        {{
                                                                            item.label
                                                                        }}

                                                                        <template
                                                                            v-if="
                                                                                open
                                                                            "
                                                                        >
                                                                            <span
                                                                                class="material-symbols-outlined"
                                                                            >
                                                                                remove
                                                                            </span>
                                                                        </template>
                                                                        <template
                                                                            v-if="
                                                                                !open
                                                                            "
                                                                        >
                                                                            <span
                                                                                class="material-symbols-outlined"
                                                                            >
                                                                                add
                                                                            </span>
                                                                        </template>
                                                                    </DisclosureButton>

                                                                    <DisclosurePanel
                                                                        as="ul"
                                                                        class="mt-1 mb-1 px-2 space-y-1"
                                                                    >
                                                                        <li
                                                                            v-for="subItem in item.children"
                                                                            :key="
                                                                                subItem
                                                                                    .route
                                                                                    .name
                                                                            "
                                                                        >
                                                                            <DisclosureButton
                                                                                class="block w-full hover:bg-myPrimaryLightGrayColor rounded-md text-left"
                                                                                :class="[
                                                                                    subItem.active
                                                                                        ? 'text-myPrimaryLinkColor'
                                                                                        : '',
                                                                                ]"
                                                                            >
                                                                                <Link
                                                                                    :class="[
                                                                                        subItem.active
                                                                                            ? 'text-myPrimaryLinkColor'
                                                                                            : '',
                                                                                    ]"
                                                                                    class="block py-3 pl-3 pr-1 text-myPrimaryDarkGrayColor text-myPrimaryDarkGrayColor"
                                                                                    :href="
                                                                                        route(
                                                                                            subItem
                                                                                                .route
                                                                                                .name,

                                                                                            subItem
                                                                                                .route
                                                                                                .parameters
                                                                                        )
                                                                                    "
                                                                                >
                                                                                    {{
                                                                                        subItem.label
                                                                                    }}
                                                                                </Link>
                                                                            </DisclosureButton>
                                                                        </li>
                                                                    </DisclosurePanel>
                                                                </Disclosure>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </nav>
                                            <!-- End replace -->
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
