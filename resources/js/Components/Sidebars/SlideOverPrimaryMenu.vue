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
        label: "Home",
        route: {
            name: "home",
            parameters: [],
        },
    },
    {
        label: "Resources",
        route: {
            name: null,
            parameters: [],
        },
        children: [
            {
                label: "Terms of Service",
                route: {
                    name: "terms.show",
                    parameters: [],
                },
            },
            {
                label: "Privacy Policy",
                route: {
                    name: "policy.show",
                    parameters: [],
                },
            },
        ],
    },
    {
        label: "Blog",
        route: {
            name: "blog.index",
            parameters: [],
        },
    },
    {
        label: "Users",
        route: {
            name: "users",
            parameters: [],
        },
    },
];
</script>

<template>
    <teleport to="body">
        <TransitionRoot as="template" :show="open">
            <Dialog
                as="div"
                class="relative z-10"
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
                                                    class="myPrimaryParagraph text-base rounded w-full py-4 font-medium"
                                                >
                                                    Menu
                                                </DialogTitle>
                                                <div
                                                    class="ml-3 flex h-7 items-center"
                                                >
                                                    <button
                                                        type="button"
                                                        class="p-2 hover:bg-myPrimaryLinkColor hover:text-white bg-gray-100 rounded-full cursor-pointer"
                                                        @click="
                                                            $emit(
                                                                'primaryMenuSlideOverButton'
                                                            )
                                                        "
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
                                            <!-- Replace with content -->
                                            <nav class="flex flex-1 flex-col">
                                                <ul
                                                    role="list"
                                                    class="flex flex-1 flex-col gap-y-7"
                                                >
                                                    <li>
                                                        <ul
                                                            role="list"
                                                            class="-mx-2 space-y-2"
                                                        >
                                                            <li
                                                                v-for="item in navigation"
                                                                :key="
                                                                    item.label
                                                                "
                                                            >
                                                                <Link
                                                                    class="block w-full py-3 pl-3 pr-1 hover:bg-myPrimaryLightGrayColor rounded-md"
                                                                    :class="[
                                                                        route().current(
                                                                            item
                                                                                .route
                                                                                ?.name
                                                                        )
                                                                            ? 'bg-myPrimaryLightGrayColor rounded-md'
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
                                                                            route().current(
                                                                                item
                                                                                    .route
                                                                                    ?.name
                                                                            )
                                                                                ? 'hover:bg-myPrimaryLightGrayColor'
                                                                                : '',
                                                                            'flex items-center justify-between w-full text-left gap-x-3 py-3 pl-3 pr-1 rounded-md',
                                                                        ]"
                                                                    >
                                                                        {{
                                                                            item.label
                                                                        }}

                                                                        <ChevronRightIcon
                                                                            :class="[
                                                                                open
                                                                                    ? 'rotate-90 text-gray-500'
                                                                                    : 'text-gray-400',
                                                                                'h-5 w-5 shrink-0',
                                                                            ]"
                                                                            aria-hidden="true"
                                                                        />
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
                                                                                    route().current(
                                                                                        subItem
                                                                                            .route
                                                                                            ?.name
                                                                                    )
                                                                                        ? 'bg-myPrimaryLightGrayColor rounded-md'
                                                                                        : '',
                                                                                ]"
                                                                            >
                                                                                <Link
                                                                                    class="block py-3 pl-3 pr-1"
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
