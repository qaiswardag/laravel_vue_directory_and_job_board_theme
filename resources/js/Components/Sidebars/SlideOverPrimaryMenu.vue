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
import { ChevronRightIcon } from "@heroicons/vue/20/solid";

// props
const props = defineProps({
    open: {
        required: true,
    },
});

// emit
const emit = defineEmits(["primaryMenuSlideOverButton"]);

// button
const primaryMenuSlideOverButton = function () {
    emit("primaryMenuSlideOverButton");
};

const navigation = [
    { name: "Blog", href: "#", current: true },
    {
        name: "Teams",
        current: false,
        children: [
            { name: "Engineering", href: "#" },
            { name: "Human Resources", href: "#" },
            { name: "Customer Success", href: "#" },
        ],
    },
    {
        name: "Projects",
        current: false,
        children: [
            { name: "GraphQL API", href: "#" },
            { name: "iOS App", href: "#" },
            { name: "Android App", href: "#" },
            { name: "New Customer Portal", href: "#" },
        ],
    },
    { name: "Calendar", href: "#", current: false },
    { name: "Documents", href: "#", current: false },
    { name: "Reports", href: "#", current: false },
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
                                                    class="myPrimaryParagraph rounded w-full py-2 italic"
                                                >
                                                    Menu
                                                </DialogTitle>
                                                <div
                                                    class="ml-3 flex h-7 items-center"
                                                >
                                                    <button
                                                        type="button"
                                                        class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50"
                                                        @click="
                                                            $emit(
                                                                'primaryMenuSlideOverButton'
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
                                                            class="-mx-2 space-y-1"
                                                        >
                                                            <li
                                                                v-for="item in navigation"
                                                                :key="item.name"
                                                            >
                                                                <a
                                                                    v-if="
                                                                        !item.children
                                                                    "
                                                                    :href="
                                                                        item.href
                                                                    "
                                                                    :class="[
                                                                        item.current
                                                                            ? 'bg-gray-50'
                                                                            : 'hover:bg-gray-50',
                                                                        'block py-2 pr-2 pl-10 text-sm leading-6 font-semibold text-gray-700 border-2 border-gray-400',
                                                                    ]"
                                                                    >{{
                                                                        item.name
                                                                    }}</a
                                                                >
                                                                <Disclosure
                                                                    as="div"
                                                                    v-else
                                                                    v-slot="{
                                                                        open,
                                                                    }"
                                                                >
                                                                    <DisclosureButton
                                                                        :class="[
                                                                            item.current
                                                                                ? 'bg-gray-50'
                                                                                : 'hover:bg-gray-50',
                                                                            'flex items-center w-full text-left p-2 gap-x-3 text-sm leading-6 font-semibold text-gray-700 border-2 border-gray-400',
                                                                        ]"
                                                                    >
                                                                        <ChevronRightIcon
                                                                            :class="[
                                                                                open
                                                                                    ? 'rotate-90 text-gray-500'
                                                                                    : 'text-gray-400',
                                                                                'h-5 w-5 shrink-0',
                                                                            ]"
                                                                            aria-hidden="true"
                                                                        />
                                                                        {{
                                                                            item.name
                                                                        }}
                                                                    </DisclosureButton>
                                                                    <DisclosurePanel
                                                                        as="ul"
                                                                        class="mt-1 mb-1 px-2 space-y-1"
                                                                    >
                                                                        <li
                                                                            v-for="subItem in item.children"
                                                                            :key="
                                                                                subItem.name
                                                                            "
                                                                        >
                                                                            <DisclosureButton
                                                                                as="a"
                                                                                :href="
                                                                                    subItem.href
                                                                                "
                                                                                :class="[
                                                                                    subItem.current
                                                                                        ? 'bg-gray-50'
                                                                                        : 'hover:bg-gray-50',
                                                                                    'block py-2 pr-2 pl-9 text-sm leading-6 text-gray-700 border-2 border-gray-400 bg-purple-50',
                                                                                ]"
                                                                                >{{
                                                                                    subItem.name
                                                                                }}</DisclosureButton
                                                                            >
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
