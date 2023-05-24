<script setup>
import { Link } from "@inertiajs/vue3";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { Head, router } from "@inertiajs/vue3";
import DropdownLink from "@/Components/Dropdowns/DropdownLink.vue";
import {
    CodeBracketIcon,
    EllipsisVerticalIcon,
    FlagIcon,
    StarIcon,
} from "@heroicons/vue/24/outline";

defineProps({
    routesArray: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div class="myPrimarySection">
        <div class="bg-white px-4 py-5 sm:px-6">
            <div class="flex items-center space-x-3 gap-2 md:flex-row sm:flex-">
                <div class="min-w-0 flex-1">
                    <p class="mySecondaryHeader my-0">
                        <slot name="title" />
                    </p>
                    <p class="myPrimaryParagraph text-xs pt-2">
                        <slot name="subTitle" />
                    </p>
                </div>
                <slot name="buttons" />
                <div class="flex flex-shrink-0 self-center">
                    <Menu as="div" class="relative inline-block text-left">
                        <div>
                            <MenuButton
                                class="-m-2 flex items-center rounded-full p-2 text-gray-400 hover:text-gray-600"
                            >
                                <EllipsisVerticalIcon
                                    class="h-5 w-5"
                                    aria-hidden="true"
                                />
                            </MenuButton>
                        </div>

                        <transition
                            enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <MenuItems
                                class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            >
                                <p
                                    class="myPrimaryParagraph flex items-center gap-2 pl-4 pr-2 py-3 text-xs text-myPrimaryMediumGrayColor"
                                >
                                    <span>Overview</span>
                                </p>
                                <div
                                    v-for="routeItem in routesArray"
                                    :key="routeItem.route.name"
                                    class="py-1"
                                    :class="{
                                        'bg-gray-50': route().current(
                                            routeItem.route.name
                                        ),
                                    }"
                                >
                                    <DropdownLink
                                        :href="
                                            route(
                                                routeItem.route.name,
                                                routeItem.route.parameters
                                            )
                                        "
                                        :active="
                                            route().current(
                                                routeItem.route.name
                                            )
                                        "
                                    >
                                        <div class="flex gap-2 items-center">
                                            <p>
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    class="w-4 h-4"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"
                                                    />
                                                </svg>
                                            </p>
                                            <p>
                                                {{ routeItem.label }}
                                            </p>
                                        </div>
                                    </DropdownLink>
                                </div>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </div>
        </div>
    </div>
</template>
