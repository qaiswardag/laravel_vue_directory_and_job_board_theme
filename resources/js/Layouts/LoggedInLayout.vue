<script setup>
import { ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import DropdownLink from "@/Components/Dropdowns/DropdownLink.vue";
import Banner from "@/Components/Banners/Banner.vue";
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";
import ResponsiveNavLink from "@/Components/Navbars/ResponsiveNavLink.vue";
import SideBarLink from "@/Components/MenuLinks/SideBarLink.vue";
import LoggedInSidebar from "@/Components/Sidebars/LoggedInSidebar.vue";
import LoggedInNavbar from "@/Components/Navbars/LoggedInNavbar.vue";
import Flash from "@/Components/Actions/Flash.vue";

import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    Bars3BottomLeftIcon,
    BellIcon,
    CalendarIcon,
    ChartBarIcon,
    FolderIcon,
    HomeIcon,
    InboxIcon,
    UsersIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import ApplicationMark from "@/Components/MarkComponents/ApplicationMark.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";

defineProps({
    title: {
        required: true,
    },
});

// DOM is loaded
const isDOMLoaded = ref(false);

router.on("start", () => {
    isDOMLoaded.value = true;
});
router.on("finish", () => {
    isDOMLoaded.value = false;
});

const sidebarOpen = ref(false);
</script>

<template>
    <Head>
        <title>{{ title }}</title>
        <!-- head-key makes sure to only add one meta key -->
        <meta
            type="description"
            content="myself.ae Fashion and Jobs"
            head-key="description"
        />
    </Head>
    <Banner />
    <Flash :flash="$page.props.flash"></Flash>

    <!-- <div v-if="isDOMLoaded">
        <FullScreenSpinner></FullScreenSpinner>
    </div> -->

    <!-- Static sidebar for mobile - start -->
    <TransitionRoot as="template" :show="sidebarOpen">
        <Dialog
            as="div"
            class="relative z-40 md:hidden"
            @close="sidebarOpen = false"
        >
            <TransitionChild
                as="template"
                enter="transition-opacity ease-linear duration-100"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="transition-opacity ease-linear duration-100"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-myPrimaryDarkGrayColor bg-opacity-75"
                />
            </TransitionChild>

            <div class="fixed inset-0 z-40 flex">
                <TransitionChild
                    as="template"
                    enter="transition ease-in-out duration-100 transform"
                    enter-from="-translate-x-full"
                    enter-to="translate-x-0"
                    leave="transition ease-in-out duration-100 transform"
                    leave-from="translate-x-0"
                    leave-to="-translate-x-full"
                >
                    <DialogPanel
                        class="relative flex w-full max-w-xs flex-1 flex-col bg-white pt-5 pb-4"
                    >
                        <TransitionChild
                            as="template"
                            enter="ease-in-out duration-100"
                            enter-from="opacity-0"
                            enter-to="opacity-100"
                            leave="ease-in-out duration-100"
                            leave-from="opacity-100"
                            leave-to="opacity-0"
                        >
                            <div class="absolute top-0 right-0 -mr-12 pt-2">
                                <button
                                    type="button"
                                    class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                    @click="sidebarOpen = false"
                                >
                                    <span class="sr-only">Close sidebar</span>
                                    <XMarkIcon
                                        class="h-6 w-6 text-white"
                                        aria-hidden="true"
                                    />
                                </button>
                            </div>
                        </TransitionChild>
                        <div class="flex flex-shrink-0 items-center px-4">
                            <img
                                class="h-8 w-auto"
                                src="https://global-uploads.webflow.com/5e157547d6f791d34ea4e2bf/6087f2b060c7a92408bac811_logo.svg"
                                alt="Your Company"
                            />
                        </div>
                        <div class="mt-5 h-0 flex-1 overflow-y-auto">
                            <LoggedInSidebar></LoggedInSidebar>
                        </div>
                    </DialogPanel>
                </TransitionChild>
                <div class="w-14 flex-shrink-0" aria-hidden="true">
                    <!-- Dummy element to force sidebar to shrink to fit close icon -->
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
    <!-- Static sidebar for mobile - end -->

    <!-- Static sidebar for desktop - start -->
    <div class="hidden md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div
            class="flex flex-grow flex-col overflow-y-auto border-r border-gray-200 bg-white pt-5"
        >
            <div class="flex flex-shrink-0 items-center px-4">
                <img
                    class="h-8 w-auto"
                    src="https://global-uploads.webflow.com/5e157547d6f791d34ea4e2bf/6087f2b060c7a92408bac811_logo.svg"
                    alt="Your Company"
                />
            </div>
            <div class="mt-5 flex flex-grow flex-col">
                <LoggedInSidebar></LoggedInSidebar>
            </div>
        </div>
    </div>
    <!-- Static sidebar for desktop - end -->

    <!-- Topbar - start -->
    <div class="flex flex-1 flex-col md:pl-64">
        <div class="sticky top-0 z-10 flex h-16 flex-shrink-0">
            <button
                type="button"
                class="border-r px-4 text-myPrimaryDarkGrayColor focus:outline-none focus:ring-2 focus:ring-inset focus:ring-myPrimaryBrandColor md:hidden bg-white border-b-2 border-gray-200"
                @click="sidebarOpen = true"
            >
                <span class="sr-only">Open sidebar</span>
                <Bars3BottomLeftIcon class="h-6 w-6" aria-hidden="true" />
            </button>
            <LoggedInNavbar></LoggedInNavbar>
        </div>

        <main class="flex-1 bg-gray-50">
            <header v-if="$slots.header">
                <slot name="header" />
                <slot name="breadcrumbs" />
            </header>
            <div
                class="mx-auto max-w-7xl min-h-screen px-4 sm:px-6 md:px-8 pb-12"
            >
                <main>
                    <slot />
                </main>
            </div>
        </main>
    </div>
    <!--Topbar - end -->
</template>
