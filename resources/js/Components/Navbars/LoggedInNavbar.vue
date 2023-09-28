<script setup>
import SlideOverNotifications from "@/Components/Sidebars/SlideOverNotifications.vue";
import SlideOverPrimaryMenu from "@/Components/Sidebars/SlideOverPrimaryMenu.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
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
    AdjustmentsHorizontalIcon,
    ArrowRightCircleIcon,
    ArrowRightIcon,
    Bars3BottomLeftIcon,
    Bars3Icon,
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
import { MagnifyingGlassIcon } from "@heroicons/vue/24/outline";
import ApplicationMark from "@/Components/MarkComponents/ApplicationMark.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import DynamicMenuModal from "@/Components/Modals/DynamicMenuModal.vue";

// updated menu
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import {
    ChevronDownIcon,
    PhoneIcon,
    PlayCircleIcon,
} from "@heroicons/vue/24/outline";
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

// modal search anything
const modalShowSearchAnything = ref(false);

// modal menu content
const modalShowAccountMenu = ref(false);
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

const handleMenuUserTeamModal = () => {
    // handle show modal for unique content
    modalShowAccountMenu.value = true;
    // handle click
    firstModalMenuButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowAccountMenu.value = false;
    };
    // end menu modal
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
    showPrimaryMenuSlideOver.value = true;
};
// handle primary menu window
const primaryMenuSlideOverButton = function () {
    showPrimaryMenuSlideOver.value = false;
};
// handle notifications window
const handleNotificationsSlideOver = function () {
    showNotificationsSlideOver.value = true;
};
// handle notifications window
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
    <SlideOverPrimaryMenu
        :open="showPrimaryMenuSlideOver"
        @primaryMenuSlideOverButton="primaryMenuSlideOverButton"
    >
    </SlideOverPrimaryMenu>
    <DynamicMenuModal
        v-if="$page.props !== null && $page.props.user !== null"
        :show="modalShowAccountMenu"
        @firstModalMenuButtonFunction="firstModalMenuButtonFunction"
    >
    </DynamicMenuModal>
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
                        class="text-sm cursor-pointer block w-full h-full pl-8 pr-3 border-transparent placeholder-gray-500 focus:outline-none border-0 focus:ring-0 font-normal text-myPrimaryDarkGrayColor rounded-md py-4 px-3"
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
            <Link :href="route('home')">
                <div
                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                >
                    <HomeIcon class="myMediumIcon" aria-hidden="true" />
                </div>
            </Link>

            <button
                v-if="
                    $page.props.user &&
                    $page.props.user.profile_photo_path !== null
                "
                type="button"
                @click="handleMenuUserTeamModal"
                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
            >
                <div
                    class="h-10 w-10 flex-shrink-0"
                    v-if="
                        $page.props.user &&
                        $page.props.user.profile_photo_path !== null
                    "
                >
                    <img
                        class="object-cover h-10 w-10 rounded-full flex-shrink-0"
                        :src="`/storage/${$page.props.user.profile_photo_path}`"
                        :alt="
                            $page.props.user.first_name +
                            $page.props.user.last_name
                        "
                    />
                </div>
            </button>
            <button
                v-if="
                    $page.props.user &&
                    $page.props.user.profile_photo_path === null
                "
                @click="handleMenuUserTeamModal"
                type="button"
                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
            >
                <UserIcon class="myMediumIcon" aria-hidden="true" />
            </button>

            <button
                @click="handleNotificationsSlideOver"
                type="button"
                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
            >
                <span class="sr-only">View notifications</span>
                <BellIcon class="myMediumIcon" aria-hidden="true" />
            </button>

            <button
                @click="handlePrimaryMenuSlideOver"
                type="button"
                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
            >
                <span class="sr-only">View Menu</span>
                <Bars3Icon class="myMediumIcon" aria-hidden="true" />
            </button>
        </nav>
    </header>
</template>
