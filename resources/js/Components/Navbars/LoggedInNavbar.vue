<script setup>
import SlideOverNotifications from "@/Components/Sidebars/SlideOverNotifications.vue";
import SlideOverPrimaryMenu from "@/Components/Sidebars/SlideOverPrimaryMenu.vue";
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
    UserIcon,
    UsersIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { MagnifyingGlassIcon } from "@heroicons/vue/20/solid";
import ApplicationMark from "@/Components/MarkComponents/ApplicationMark.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import DynamicMenuModal from "@/Components/Modals/DynamicMenuModal.vue";

// updated menu
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import {
    ChevronDownIcon,
    PhoneIcon,
    PlayCircleIcon,
} from "@heroicons/vue/20/solid";
import {
    ArrowPathIcon,
    ChartPieIcon,
    CursorArrowRaysIcon,
    FingerPrintIcon,
    SquaresPlusIcon,
    Bars2Icon,
} from "@heroicons/vue/24/outline";

// slide over
const showNotificationsSlideOver = ref(false);
const showPrimaryMenuSlideOver = ref(false);

// modal
const modalShowTeamMenu = ref(false);
const modalShowSearchAnything = ref(false);
const modalShowSwitchTeams = ref(false);
const modalShowLogout = ref(false);

// modal menu content
const titleMenuModal = ref("");
const firstMenuButtonModal = ref("");
// set menu modal handle functions
const firstModalMenuButtonFunction = ref(null);

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

const handleMenuMenuItem = () => {
    // handle show modal for unique content
    modalShowTeamMenu.value = true;
    // set modal standards
    titleMenuModal.value = "Menu";
    // handle click
    firstModalMenuButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowTeamMenu.value = false;
    };
    // end menu modal
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

// handle primary slideoer menu
const handlePrimaryMenuSlideOver = function () {
    console.log("came here");
    showPrimaryMenuSlideOver.value = true;
};
// handle notifications window
const handleNotificationsSlideOver = function () {
    showNotificationsSlideOver.value = true;
};
// close notifications window
const notificationsSlideOverButton = function () {
    showNotificationsSlideOver.value = false;
};
// close primary menu window
const primaryMenuSlideOverButton = function () {
    showPrimaryMenuSlideOver.value = false;
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
    <SlideOverPrimaryMenu
        :open="showPrimaryMenuSlideOver"
        @primaryMenuSlideOverButton="primaryMenuSlideOverButton"
    >
    </SlideOverPrimaryMenu>
    <DynamicMenuModal
        :show="modalShowTeamMenu"
        :title="titleMenuModal"
        @firstModalMenuButtonFunction="firstModalMenuButtonFunction"
    >
        <main>
            <div class="flex flex-col gap-1">
                <p
                    class="myPrimaryParagraph italic text-xs py-2 px-2 bg-gray-50 rounded-lg"
                >
                    Team
                </p>
                <div
                    class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50"
                >
                    <div
                        class="mt-1 flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white"
                    >
                        <svg
                            class="h-5 w-5 text-gray-600 group-hover:text-indigo-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16.712 4.33a9.027 9.027 0 011.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 00-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 010 9.424m-4.138-5.976a3.736 3.736 0 00-.88-1.388 3.737 3.737 0 00-1.388-.88m2.268 2.268a3.765 3.765 0 010 2.528m-2.268-4.796a3.765 3.765 0 00-2.528 0m4.796 4.796c-.181.506-.475.982-.88 1.388a3.736 3.736 0 01-1.388.88m2.268-2.268l4.138 3.448m0 0a9.027 9.027 0 01-1.306 1.652c-.51.51-1.064.944-1.652 1.306m0 0l-3.448-4.138m3.448 4.138a9.014 9.014 0 01-9.424 0m5.976-4.138a3.765 3.765 0 01-2.528 0m0 0a3.736 3.736 0 01-1.388-.88 3.737 3.737 0 01-.88-1.388m2.268 2.268L7.288 19.67m0 0a9.024 9.024 0 01-1.652-1.306 9.027 9.027 0 01-1.306-1.652m0 0l4.138-3.448M4.33 16.712a9.014 9.014 0 010-9.424m4.138 5.976a3.765 3.765 0 010-2.528m0 0c.181-.506.475-.982.88-1.388a3.736 3.736 0 011.388-.88m-2.268 2.268L4.33 7.288m6.406 1.18L7.288 4.33m0 0a9.024 9.024 0 00-1.652 1.306A9.025 9.025 0 004.33 7.288"
                            ></path>
                        </svg>
                    </div>
                    <div>
                        <a href="#" class="font-semibold text-gray-900">
                            Help center
                            <span class="absolute inset-0"></span>
                        </a>
                        <p class="mt-1 text-gray-600">
                            Get all of your questions answered
                        </p>
                    </div>
                </div>
                <p
                    class="myPrimaryParagraph italic text-xs py-2 px-2 bg-gray-50 rounded-lg"
                >
                    Account
                </p>

                <form @submit.prevent="handleLogout">
                    <div
                        @click="handleLogout"
                        class="hover:bg-gray-50 group relative flex gap-x-6 rounded-lg p-4 cursor-pointer"
                    >
                        <div
                            class="mt-1 flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white"
                        >
                            <svg
                                class="h-5 w-5 text-gray-600 group-hover:text-indigo-600"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"
                                />
                            </svg>
                        </div>
                        <div class="flex items-center">
                            <div class="font-semibold text-gray-900">
                                Log Out
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </DynamicMenuModal>
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

    <!-- search anything - start -->
    <div class="flex flex-1">
        <div class="ml-4 flex items-center md:ml-6 gap-8">
            <form class="w-full flex md:ml-0" @submit.prevent>
                <div
                    class="relative w-full text-myPrimaryDarkGrayColor focus-within:text-myPrimaryDarkGrayColor"
                >
                    <div
                        class="absolute inset-y-0 left-0 flex items-center pointer-events-none pl-3"
                    >
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
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
                            />
                        </svg>
                    </div>
                    <input
                        id="search-field"
                        class="text-sm cursor-pointer block w-full h-full pl-8 pr-3 border-transparent placeholder-gray-500 focus:outline-none border-0 focus:ring-0 font-medium text-myPrimaryDarkGrayColor rounded-md py-6 px-3"
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
    <!-- search anything - end -->
    <header class="w-6/1 text-sm">
        <nav
            class="mx-auto flex gap-4 max-w-7xl items-center justify-end px-6 lg:px-8"
            aria-label="Global"
        >
            <Link
                class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-200"
                :href="route('home')"
            >
                <HomeIcon class="h-6 w-6" aria-hidden="true" />
            </Link>

            <div
                @click="handleMenuMenuItem"
                class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50"
            >
                <div
                    v-if="
                        $page.props.user &&
                        $page.props.user.profile_photo_path !== null
                    "
                >
                    <img
                        class="object-cover w-9 h-9 rounded-full flex-shrink-0"
                        :src="`/uploads/${$page.props.user.profile_photo_path}`"
                        :alt="
                            $page.props.user.first_name +
                            $page.props.user.last_name
                        "
                    />
                </div>
                <button
                    v-if="
                        $page.props.user &&
                        $page.props.user.profile_photo_path === null
                    "
                    @click="handleMenuMenuItem"
                    type="button"
                    class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-200"
                >
                    <UserIcon class="h-6 w-6" aria-hidden="true" />
                </button>
            </div>

            <button
                @click="handleNotificationsSlideOver"
                type="button"
                class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-200"
            >
                <span class="sr-only">View notifications</span>
                <BellIcon class="h-6 w-6" aria-hidden="true" />
            </button>

            <button
                @click="handlePrimaryMenuSlideOver"
                type="button"
                class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-200"
            >
                <span class="sr-only">View notifications</span>
                <Bars2Icon class="h-6 w-6" aria-hidden="true" />
            </button>
        </nav>
    </header>
</template>
