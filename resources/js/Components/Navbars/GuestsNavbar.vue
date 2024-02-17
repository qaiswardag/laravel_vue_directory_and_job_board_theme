<script setup>
import SlideOverNotifications from "@/Components/Sidebars/SlideOverNotifications.vue";
import SlideOverPrimaryMenu from "@/Components/Sidebars/SlideOverPrimaryMenu.vue";
import { ref } from "vue";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";

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
    AdjustmentsVerticalIcon,
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
import {
    EllipsisHorizontalIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/24/outline";
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
    <SlideOverNotifications
        v-if="$page.props.user !== null"
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

    <header class="w-full text-sm">
        <nav
            class="mx-auto flex myPrimaryGap items-center justify-end px-6 lg:px-8"
            aria-label="Global"
        >
            <div class="flex myPrimaryGap items-center">
                <template v-if="$page.props.user !== null">
                    <Link :href="route('dashboard')">
                        <button
                            type="button"
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                        >
                            <span class="material-symbols-outlined">
                                play_shapes
                            </span>
                        </button>
                    </Link>
                </template>

                <template v-if="$page.props.user === null">
                    <Link
                        class="text-black focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor font-medium"
                        :href="route('login')"
                    >
                        <span> Login </span>
                    </Link>
                </template>
                <template v-if="$page.props.user === null">
                    <Link
                        class="text-black focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor font-medium"
                        :href="route('register')"
                    >
                        <span> Sign up </span>
                    </Link>
                </template>
                <template v-if="$page.props.user !== null">
                    <button
                        v-if="
                            $page.props.user.user_photo &&
                            Array.isArray($page.props.user.user_photo) &&
                            $page.props.user.user_photo.length !== 0
                        "
                        type="button"
                        @click="handleMenuUserTeamModal"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                    >
                        <div class="h-10 w-10 flex-shrink-0">
                            <ThumbnailSmallImageSlider
                                :images="$page.props.user.user_photo"
                                imageSize="thumbnail_path"
                                imageHeight="h-10"
                                imageWidth="w-10"
                                :roundedFull="true"
                            ></ThumbnailSmallImageSlider>
                        </div>
                    </button>
                    <button
                        v-if="
                            $page.props.user.user_photo &&
                            Array.isArray($page.props.user.user_photo) &&
                            $page.props.user.user_photo.length === 0
                        "
                        @click="handleMenuUserTeamModal"
                        type="button"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                    >
                        <span class="myMediumIcon material-symbols-outlined">
                            person
                        </span>
                    </button>
                </template>

                <template v-if="$page.props.user !== null">
                    <button
                        @click="handleNotificationsSlideOver"
                        type="button"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white hover:fill-white focus-visible:ring-0"
                    >
                        <span class="sr-only">View notifications</span>
                        <span class="myMediumIcon material-symbols-outlined">
                            notifications
                        </span>
                    </button>
                </template>

                <button
                    @click="handlePrimaryMenuSlideOver"
                    type="button"
                    class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                >
                    <span class="sr-only">View Menu</span>
                    <span class="myMediumIcon material-symbols-outlined">
                        drag_handle
                    </span>
                </button>
            </div>
        </nav>
    </header>
</template>
