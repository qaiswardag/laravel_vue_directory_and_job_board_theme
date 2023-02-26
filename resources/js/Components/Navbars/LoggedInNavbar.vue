<script setup>
import SlideOverNotifications from "@/Components/Sidebars/SlideOverNotifications.vue";
import { ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import SearchAnythingModal from "@/Components/Modals/SearchAnythingModal.vue";
import DropdownLink from "@/Components/Dropdowns/DropdownLink.vue";
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

// notification slide over
const showNotificationsSlideOver = ref(false);

// search modal
const modalShowSearchAnything = ref(false);
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
</script>
<template>
    <SearchAnythingModal
        :open="modalShowSearchAnything"
        @searchAnythingModalButton="searchAnythingModalButton"
    >
    </SearchAnythingModal>

    <SlideOverNotifications
        :open="showNotificationsSlideOver"
        @notificationsSlideOverButton="notificationsSlideOverButton"
    >
    </SlideOverNotifications>
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
    <div
        class="bg-white flex flex-1 justify-between pr-4 border-b-2 border-gray-800"
    >
        <div class="flex flex-1">
            <div class="ml-4 flex items-center md:ml-6 gap-8">
                <form class="w-full flex md:ml-0" @submit.prevent>
                    <label for="search-field" class="sr-only">
                        Search anything...
                    </label>
                    <div
                        class="relative w-full text-myPrimaryDarkGrayColor focus-within:text-myPrimaryDarkGrayColor"
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
                            class="cursor-pointer block w-full h-full pl-8 pr-3 py-2 border-transparent text-myPrimaryDarkGrayColor font-semibold placeholder-gray-500 placeholder:font-semibold focus:outline-none focus:placeholder-myPrimaryDarkGrayColor focus:ring-0 focus:border-transparent sm:text-sm"
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

            <Link class="myPrimaryParagraph" :href="route('home')"> Home </Link>

            <template
                v-if="
                    $page.props.user.all_teams.length > 0 &&
                    !$page.props.user.current_team &&
                    $page.props.jetstream.hasTeamFeatures
                "
            >
                <div
                    class="flex gap-2 max-w-xs items-center rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-myPrimaryBrandColor focus:ring-offset-2 py-2 px-2"
                >
                    <Link
                        :href="
                            route(
                                'user.teams.switch',
                                $page.props.user.current_team
                            )
                        "
                        :class="{
                            myPrimaryLink: route().current('user.teams.switch'),
                        }"
                    >
                        No Team Selected
                    </Link>
                </div>
            </template>

            <template
                v-if="
                    $page.props.user.all_teams.length > 0 &&
                    $page.props.user.current_team &&
                    $page.props.jetstream.hasTeamFeatures
                "
            >
                <Menu as="div" class="relative">
                    <MenuButton
                        class="flex gap-2 max-w-xs items-center rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-myPrimaryBrandColor focus:ring-offset-2 py-2 px-2"
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
                                class="myPrimaryParagraph block px-4 py-2 text-xs text-myPrimaryMediumGrayColor"
                                >Settings</span
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
                        class="flex ml-3 gap-2 max-w-xs items-center rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-myPrimaryBrandColor focus:ring-offset-2 pr-2"
                    >
                        <span class="sr-only">Open user menu</span>

                        <div class="flex flex-col justify-center items-center">
                            <div
                                v-show="
                                    $page.props.user &&
                                    $page.props.user.profile_photo_path !== null
                                "
                            >
                                <img
                                    class="object-cover w-8 h-8 rounded-full"
                                    :src="$page.props.user.profile_photo_url"
                                    :alt="
                                        $page.props.user.first_name +
                                        $page.props.user.last_name
                                    "
                                />
                            </div>

                            <div
                                v-show="
                                    $page.props.user &&
                                    $page.props.user.profile_photo_path === null
                                "
                                class="w-8 h-8 gap-0.5 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-semibold text-white"
                            >
                                <span>
                                    {{
                                        $page.props.user.first_name
                                            .charAt(0)
                                            .toUpperCase()
                                    }}
                                </span>
                                <span>
                                    {{
                                        $page.props.user.last_name
                                            .charAt(0)
                                            .toUpperCase()
                                    }}
                                </span>
                            </div>
                        </div>

                        <span>
                            {{ $page.props.user.first_name }}
                            {{ $page.props.user.last_name }}</span
                        >
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
                class="rounded-full p-1 text-myPrimaryDarkGrayColor hover:text-myPrimaryDarkGrayColor focus:outline-none focus:ring-2 focus:ring-myPrimaryBrandColor focus:ring-offset-2"
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
</template>
