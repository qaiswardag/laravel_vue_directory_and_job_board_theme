<script setup>
import { Link } from "@inertiajs/vue3";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { router } from "@inertiajs/vue3";
import DropdownLink from "@/Components/Dropdowns/DropdownLink.vue";

import {
    ArrowLongRightIcon,
    ArrowRightIcon,
    CodeBracketIcon,
    EllipsisVerticalIcon,
    FlagIcon,
    StarIcon,
} from "@heroicons/vue/24/outline";

defineProps({
    routesArray: {
        type: Array,
        required: false,
    },
});
</script>

<template>
    <div class="myPrimarySection">
        <div
            class="overflow-x-scrollpx-2 py-6 px-4 rounded-full bg-myPrimaryLightGrayColor"
        >
            <div
                class="flex items-center justify-between space-x-3 gap-2 md:flex-row"
            >
                <div class="min-w-0 w-2/3">
                    <p class="myQuaternaryHeader my-0">
                        <slot name="title" />
                    </p>
                    <template v-if="$slots.subTitle">
                        <p class="myPrimaryParagraph pt-2 text-sm">
                            <slot name="subTitle" />
                        </p>
                    </template>
                </div>

                <div
                    class="flex flex-shrink-0 items-center self-center myPrimaryGap"
                >
                    <slot name="buttons" />

                    <Menu
                        v-if="routesArray"
                        as="div"
                        class="relative inline-block text-left"
                    >
                        <div>
                            <MenuButton
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            >
                                <span
                                    class="myMediumIcon material-symbols-outlined"
                                >
                                    more_vert
                                </span>
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
                                            <ArrowLongRightIcon
                                                class="w-4 h-4"
                                            ></ArrowLongRightIcon>
                                            <p>{{ routeItem.label }}</p>
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
