<script setup>
import SlideOverNotifications from "@/Components/Sidebars/SlideOverNotifications.vue";
import SlideOverPrimaryMenu from "@/Components/Sidebars/SlideOverPrimaryMenu.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
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

    <header class="w-6/1 text-sm">
        <nav
            class="mx-auto flex gap-4 max-w-7xl items-center justify-end px-6 lg:px-8"
            aria-label="Global"
        >
            <template v-if="$page.props.user !== null">
                <Link :href="route('dashboard')">
                    <div
                        class="w-10 h-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                    >
                        <AdjustmentsVerticalIcon
                            class="shrink-0 h-4 w-4 m-2 stroke-2"
                            aria-hidden="true"
                        />
                    </div>
                </Link>
            </template>

            <template v-if="$page.props.user === null">
                <Link
                    class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50 font-medium"
                    :href="route('login')"
                >
                    Login
                </Link>
            </template>
            <template v-if="$page.props.user === null">
                <Link
                    class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50 font-medium"
                    :href="route('register')"
                >
                    Register
                </Link>
            </template>
            <template v-if="$page.props.user !== null">
                <div
                    @click="handleMenuUserTeamModal"
                    class="w-10 h-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                >
                    <div
                        class="h-10 w-10 flex-shrink-0"
                        v-if="
                            $page.props.user &&
                            $page.props.user.profile_photo_path !== null
                        "
                    >
                        <img
                            class="object-cover w-10 h-10 rounded-full flex-shrink-0"
                            :src="`/storage/${$page.props.user.profile_photo_path}`"
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
                        @click="handleMenuUserTeamModal"
                        type="button"
                        class="w-10 h-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                    >
                        <UserIcon
                            class="shrink-0 h-4 w-4 m-2 stroke-2"
                            aria-hidden="true"
                        />
                    </button>
                </div>
            </template>

            <template v-if="$page.props.user !== null">
                <button
                    @click="handleNotificationsSlideOver"
                    type="button"
                    class="w-10 h-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                >
                    <span class="sr-only">View notifications</span>
                    <BellIcon
                        class="shrink-0 h-4 w-4 m-2 stroke-2"
                        aria-hidden="true"
                    />
                </button>
            </template>

            <button
                @click="handlePrimaryMenuSlideOver"
                type="button"
                class="w-10 h-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
            >
                <span class="sr-only">View Menu</span>
                <Bars3Icon
                    class="shrink-0 h-4 w-4 m-2 stroke-2"
                    aria-hidden="true"
                />
            </button>
        </nav>
    </header>
</template>
