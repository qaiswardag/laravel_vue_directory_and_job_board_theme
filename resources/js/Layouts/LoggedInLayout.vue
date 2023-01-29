<script setup>
import { ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import SearchAnythingModal from "@/Components/Modals/SearchAnythingModal.vue";
import DropdownLink from "@/Components/Dropdowns/DropdownLink.vue";
import Banner from "@/Components/Banners/Banner.vue";
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";
import ResponsiveNavLink from "@/Components/Navbars/ResponsiveNavLink.vue";
import SideBarLink from "@/Components/MenuLinks/SideBarLink.vue";
import SlideOverNotifications from "@/Components/Sidebars/SlideOverNotifications.vue";
import Flash from "../Components/alets/Flash.vue";
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
import { MagnifyingGlassIcon } from "@heroicons/vue/20/solid";
import ApplicationMark from "@/Components/MarkComponents/ApplicationMark.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";

defineProps({
    title: {
        required: true,
    },
});

// notifikation slide over
const showNotificationsSlideOver = ref(false);

// search modal
const modalShowSearchAnything = ref(false);
// flash modal
const modalShowFlashModal = ref(true);

const modalShowSwitchTeams = ref(false);
const modalShowLogout = ref(false);
// modal content
const typeModal = ref("");
const gridColumnModal = ref(Number(1));
const titleModal = ref("");
const descriptionModal = ref("");
const firstButtonModal = ref("");
const secondButtonModal = ref(null);
const thirdButtonModal = ref(null);
// set dynamic modal handle functions
const firstModalButtonFunction = ref(null);
const secondModalButtonFunction = ref(null);
const thirdModalButtonFunction = ref(null);

const handleSwitchToTeam = (team) => {
    // handle show modal for unique content
    modalShowSwitchTeams.value = true;
    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = `You are switching team to ${team.name}`;
    descriptionModal.value = `Are you sure you want to switch team to ${team.name}?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Swtich team";
    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowSwitchTeams.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowSwitchTeams.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        router.put(
            route("current-team.update"),
            {
                team_id: team.id,
            },
            {
                preserveState: false,
            }
        );
        // handle show modal for unique content
        modalShowSwitchTeams.value = false;
    };
    // end modal
};
const handleLogout = () => {
    // handle show modal for unique content
    modalShowLogout.value = true;
    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = "Logout";
    descriptionModal.value = "Are you sure you want to logout?";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Logout";
    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowLogout.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowLogout.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        router.post(route("logout"));
        // handle show modal for unique content
        modalShowLogout.value = false;
    };
    // end modal
};

// serach anything modal button (close)
const searchAnythingModalButton = function () {
    modalShowSearchAnything.value = false;
};

// handle serach anything click
const handleSearchAnything = function () {
    modalShowSearchAnything.value = true;
};

// handle notifications window
const handleNotificationsSlideOver = function () {
    showNotificationsSlideOver.value = true;
};
// close notifications window
const notificationsSlideOverButton = function () {
    showNotificationsSlideOver.value = false;
};

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
        <!-- head-key makes sure to only add on meta key -->
        <meta
            type="description"
            content="myself.ae Fashion and Jobs"
            head-key="description"
        />
    </Head>
    <Banner />

    <!-- <div v-if="isDOMLoaded">
        <FullScreenSpinner></FullScreenSpinner>
    </div> -->

    <SearchAnythingModal
        :open="modalShowSearchAnything"
        @searchAnythingModalButton="searchAnythingModalButton"
    >
    </SearchAnythingModal>

    <Flash :flash="$page.props.flash"></Flash>
    <SlideOverNotifications
        :open="showNotificationsSlideOver"
        @notificationsSlideOverButton="notificationsSlideOverButton"
    >
    </SlideOverNotifications>
    <DynamicModal
        :show="modalShowSwitchTeams"
        :type="typeModal"
        :gridColumnAmount="gridColumnModal"
        :title="titleModal"
        :description="descriptionModal"
        :firstButtonText="firstButtonModal"
        :secondButtonText="secondButtonModal"
        :thirdButtonText="thirdButtonModal"
        @firstModalButtonFunction="firstModalButtonFunction"
        @secondModalButtonFunction="secondModalButtonFunction"
        @thirdModalButtonFunction="thirdModalButtonFunction"
    >
        <header></header>
        <main></main>
    </DynamicModal>
    <DynamicModal
        :show="modalShowLogout"
        :type="typeModal"
        :gridColumnAmount="gridColumnModal"
        :title="titleModal"
        :description="descriptionModal"
        :firstButtonText="firstButtonModal"
        :secondButtonText="secondButtonModal"
        :thirdButtonText="thirdButtonModal"
        @firstModalButtonFunction="firstModalButtonFunction"
        @secondModalButtonFunction="secondModalButtonFunction"
        @thirdModalButtonFunction="thirdModalButtonFunction"
    >
        <header></header>
        <main></main>
    </DynamicModal>

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
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75" />
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
                                src="https://tailwindui.com/img/logos/mark.svg?color=fuchsia&shade=600"
                                alt="Your Company"
                            />
                        </div>
                        <div class="mt-5 h-0 flex-1 overflow-y-auto">
                            <nav class="space-y-1 px-2" aria-label="menu">
                                <SideBarLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="text-gray-500 mr-4 flex-shrink-0 h-6 w-6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
                                        />
                                    </svg>
                                    Dashboard
                                </SideBarLink>
                                <SideBarLink
                                    :href="route('profile.show')"
                                    :active="route().current('profile.show')"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="text-gray-500 mr-4 flex-shrink-0 h-6 w-6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
                                        />
                                    </svg>
                                    Your Profile
                                </SideBarLink>

                                <template
                                    v-if="
                                        $page.props.user.all_teams.length > 0 &&
                                        $page.props.jetstream.hasTeamFeatures
                                    "
                                >
                                    <SideBarLink
                                        :href="
                                            route(
                                                'teams.show',
                                                $page.props.user.current_team
                                            )
                                        "
                                        :active="route().current('teams.show')"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="text-gray-500 mr-4 flex-shrink-0 h-6 w-6"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                                            />
                                        </svg>
                                        Teams Settings
                                    </SideBarLink>
                                </template>
                            </nav>
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
                    src="https://tailwindui.com/img/logos/mark.svg?color=fuchsia&shade=600"
                    alt="Your Company"
                />
            </div>
            <div class="mt-5 flex flex-grow flex-col">
                <nav class="flex-1 space-y-1 px-2 pb-4" aria-label="menu">
                    <SideBarLink
                        :href="route('dashboard')"
                        :active="route().current('dashboard')"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="text-gray-500 mr-4 flex-shrink-0 h-6 w-6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
                            />
                        </svg>
                        Dashboard
                    </SideBarLink>
                    <SideBarLink
                        :href="route('profile.show')"
                        :active="route().current('profile.show')"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="text-gray-500 mr-4 flex-shrink-0 h-6 w-6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
                            />
                        </svg>
                        Your Profile
                    </SideBarLink>
                    <template
                        v-if="
                            $page.props.user.all_teams.length > 0 &&
                            $page.props.jetstream.hasTeamFeatures
                        "
                    >
                        <SideBarLink
                            :href="
                                route(
                                    'teams.show',
                                    $page.props.user.current_team
                                )
                            "
                            :active="route().current('teams.show')"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="text-gray-500 mr-4 flex-shrink-0 h-6 w-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                                />
                            </svg>
                            Teams Settings
                        </SideBarLink>
                    </template>

                    <SideBarLink
                        :href="route('manageTeams')"
                        :active="route().current('manageTeams')"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="text-gray-500 mr-4 flex-shrink-0 h-6 w-6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"
                            />
                        </svg>

                        Manage Teams
                    </SideBarLink>

                    <SideBarLink
                        :href="route('docs', $page.props.user.current_team)"
                        :active="route().current('docs')"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="text-gray-500 mr-4 flex-shrink-0 h-6 w-6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"
                            />
                        </svg>

                        Doc
                    </SideBarLink>
                </nav>
            </div>
        </div>
    </div>
    <!-- Static sidebar for desktop - end -->

    <!-- Topbar - start -->
    <div class="flex flex-1 flex-col md:pl-64">
        <div class="sticky top-0 z-10 flex h-16 flex-shrink-0">
            <button
                type="button"
                class="border-r px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-fuchsia-500 md:hidden bg-white border-b-2 border-gray-200"
                @click="sidebarOpen = true"
            >
                <span class="sr-only">Open sidebar</span>
                <Bars3BottomLeftIcon class="h-6 w-6" aria-hidden="true" />
            </button>
            <div
                class="bg-white flex flex-1 justify-between pr-4 border-b-2 border-gray-200"
            >
                <div class="flex flex-1">
                    <div class="ml-4 flex items-center md:ml-6 gap-8">
                        <form class="w-full flex md:ml-0" @submit.prevent>
                            <label for="search-field" class="sr-only">
                                Search anything...
                            </label>
                            <div
                                class="relative w-full text-gray-400 focus-within:text-gray-600"
                            >
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pointer-events-none"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        class="w-4 h-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
                                        />
                                    </svg>
                                </div>
                                <input
                                    id="search-field"
                                    class="cursor-pointer block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 font-semibold placeholder-gray-500 placeholder:font-semibold focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent sm:text-sm"
                                    @click.prevent="handleSearchAnything"
                                    placeholder="Search anything..."
                                    readonly
                                    type="search"
                                    autocomplete="off"
                                    name="search"
                                />
                            </div>
                        </form>
                    </div>
                </div>

                <div class="ml-4 flex items-center md:ml-6 gap-8">
                    <!--  -->
                    <!-- Teams Management desktop - start -->
                    <template
                        v-if="
                            $page.props.user.all_teams.length > 0 &&
                            $page.props.jetstream.hasTeamFeatures
                        "
                    >
                        <Menu as="div" class="relative">
                            <MenuButton
                                class="flex gap-2 max-w-xs items-center rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2"
                            >
                                <span class="sr-only">Open user menu</span>
                                <div>
                                    {{ $page.props.user.current_team.name }}
                                </div>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    class="w-4 h-4"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                    />
                                </svg>
                            </MenuButton>

                            <transition
                                enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <MenuItems
                                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                >
                                    <span
                                        class="myPrimaryParagraph block px-4 py-2 text-xs text-gray-400"
                                        >Manage Teams</span
                                    >
                                    <DropdownLink
                                        :href="
                                            route(
                                                'teams.show',
                                                $page.props.user.current_team
                                            )
                                        "
                                        :active="route().current('teams.show')"
                                    >
                                        Team Settings
                                    </DropdownLink>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </template>
                    <!-- Teams Management desktop - end -->

                    <!-- Profile dropdown - start -->
                    <Menu as="div" class="relative">
                        <div>
                            <MenuButton
                                class="flex ml-3 gap-2 max-w-xs items-center rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2"
                            >
                                <span class="sr-only">Open user menu</span>
                                <img
                                    class="h-8 w-8 rounded-full"
                                    src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                    alt=""
                                />
                                <span> {{ $page.props.user.name }}</span>
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
                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            >
                                <DropdownLink
                                    :href="route('profile.show')"
                                    :active="route().current('profile.show')"
                                >
                                    Your Profile
                                </DropdownLink>

                                <form @submit.prevent="handleLogout">
                                    <DropdownLink as="button" :active="false">
                                        Log Out
                                    </DropdownLink>
                                </form>
                            </MenuItems>
                        </transition>
                    </Menu>
                    <!-- Profile dropdown - end -->

                    <button
                        type="button"
                        class="rounded-full p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2"
                    >
                        <span class="sr-only">View notifications</span>
                        <BellIcon
                            @click="handleNotificationsSlideOver"
                            class="h-6 w-6"
                            aria-hidden="true"
                        />
                    </button>
                </div>
            </div>
        </div>

        <main class="flex-1">
            <Banner />
            <header v-if="$slots.header" class="bg-emerald-500 text-white">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
            <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
                <main>
                    <slot />
                </main>
            </div>
        </main>
    </div>
    <!--Topbar - start -->
</template>
