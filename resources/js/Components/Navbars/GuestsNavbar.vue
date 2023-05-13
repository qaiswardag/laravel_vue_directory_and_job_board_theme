<script setup>
import SlideOverNotifications from "@/Components/Sidebars/SlideOverNotifications.vue";
import SlideOverPrimaryMenu from "@/Components/Sidebars/SlideOverPrimaryMenu.vue";
import { ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
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
} from "@heroicons/vue/20/solid";
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

const showPrimaryMenuSlideOver = ref(false);
// modal
const modalShowAccountMenu = ref(false);

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

const handleMenuUserItem = () => {
    // handle show modal for unique content
    modalShowAccountMenu.value = true;
    // set modal standards
    titleMenuModal.value = "Menu";
    // handle click
    firstModalMenuButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowAccountMenu.value = false;
    };
    // end menu modal
};

const handleLogout = () => {
    router.get(route("home"));
    modalShowAccountMenu.value = false;
    router.post(route("logout"));
};

// handle primary slideoer menu
const handlePrimaryMenuSlideOver = function () {
    showPrimaryMenuSlideOver.value = true;
};

// close primary menu window
const primaryMenuSlideOverButton = function () {
    showPrimaryMenuSlideOver.value = false;
};
</script>
<template>
    <SlideOverPrimaryMenu
        :open="showPrimaryMenuSlideOver"
        @primaryMenuSlideOverButton="primaryMenuSlideOverButton"
    >
    </SlideOverPrimaryMenu>
    <DynamicMenuModal
        :show="modalShowAccountMenu"
        :title="titleMenuModal"
        @firstModalMenuButtonFunction="firstModalMenuButtonFunction"
    >
        <main>
            <div class="myPrimaryParagraph flex flex-col gap-1">
                <p
                    class="px-2 myPrimaryParagraph italic text-xs py-2 rounded-lg bg-gray-50"
                >
                    Logged in as {{ $page.props.user.first_name }}
                    {{ $page.props.user.last_name }}
                </p>
                <p
                    class="myPrimaryParagraph italic text-xs py-2 px-2 rounded-lg mt-2"
                >
                    Manage Team
                </p>

                <template
                    v-if="
                        $page.props.user.all_teams.length > 0 &&
                        $page.props.user.current_team &&
                        $page.props.jetstream.hasTeamFeatures
                    "
                >
                    <Link
                        :href="
                            route('teams.show', $page.props.user.current_team)
                        "
                    >
                        <div
                            class="group relative flex gap-x-6 rounded-lg px-4 py-2 items-center bg-gray-50 hover:underline"
                            :class="[
                                route().current('teams.show') ||
                                route().current('team.update.information') ||
                                route().current('team.members') ||
                                route().current('team.delete')
                                    ? 'bg-myPrimaryBrandColor text-white'
                                    : '',
                            ]"
                        >
                            <div
                                class="mt-1 flex h-8 w-8 flex-none items-center justify-center rounded-full bg-gray-200 border border-transparent group-hover:border-gray-300"
                            >
                                <AdjustmentsHorizontalIcon
                                    class="h-4 w-4 text-myPrimaryDarkGrayColor hover:text-myPrimaryDarkGrayColor"
                                />
                            </div>
                            <div>Team Settings</div>
                        </div>
                    </Link>
                </template>

                <p class="italic text-xs py-2 px-2 rounded-lg">
                    Manage Account
                </p>

                <Link
                    :href="route('profile.show')"
                    :active="
                        route().current('profile.show') ||
                        route().current('user.profile.update') ||
                        route().current('user.profile.password') ||
                        route().current('user.profile.security')
                    "
                >
                    <div
                        class="group relative flex gap-x-6 rounded-lg px-4 py-2 items-center bg-gray-50 hover:underline"
                        :class="[
                            route().current('profile.show') ||
                            route().current('user.profile.update') ||
                            route().current('user.profile.password') ||
                            route().current('user.profile.security')
                                ? 'bg-myPrimaryBrandColor text-white'
                                : '',
                        ]"
                    >
                        <div
                            class="mt-1 flex h-8 w-8 flex-none items-center justify-center rounded-full bg-gray-200 border border-transparent group-hover:border-gray-300"
                        >
                            <div
                                class="h-8 w-8 flex-shrink-0"
                                v-if="
                                    $page.props.user &&
                                    $page.props.user.profile_photo_path !== null
                                "
                            >
                                <img
                                    class="object-cover w-8 h-8 rounded-full flex-shrink-0"
                                    :src="`/storage/${$page.props.user.profile_photo_path}`"
                                    :alt="
                                        $page.props.user.first_name +
                                        $page.props.user.last_name
                                    "
                                />
                            </div>

                            <template
                                v-if="
                                    $page.props.user &&
                                    $page.props.user.profile_photo_path === null
                                "
                            >
                                <UserIcon
                                    class="h-4 w-4 text-myPrimaryDarkGrayColor hover:text-myPrimaryDarkGrayColor"
                                />
                            </template>
                        </div>
                        <div>Your Profile</div>
                    </div>
                </Link>

                <form @submit.prevent="handleLogout">
                    <div
                        @click="handleLogout"
                        class="hover:bg-gray-50 group relative flex gap-x-6 rounded-lg px-4 py-2 cursor-pointer bg-gray-50 items-center hover:underline"
                    >
                        <div
                            class="mt-1 flex h-8 w-8 flex-none items-center justify-center rounded-full bg-gray-200 border border-transparent group-hover:border-gray-300"
                        >
                            <ArrowRightIcon
                                class="h-4 w-4 text-myPrimaryDarkGrayColor hover:text-myPrimaryDarkGrayColor"
                            />
                        </div>
                        <div>Log out</div>
                    </div>
                </form>
            </div>
        </main>
    </DynamicMenuModal>

    <header class="w-6/1 text-sm">
        <nav
            class="mx-auto flex gap-4 max-w-7xl items-center justify-end px-6 lg:px-8"
            aria-label="Global"
        >
            <template v-if="$page.props.user !== null">
                <Link
                    class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-200"
                    :href="route('dashboard')"
                >
                    <AdjustmentsVerticalIcon
                        class="h-6 w-6"
                        aria-hidden="true"
                    />
                </Link>
            </template>
            <template v-if="$page.props.user === null">
                <Link
                    class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50"
                    :href="route('home')"
                >
                    Home
                </Link>
            </template>
            <template v-if="$page.props.user === null">
                <Link
                    class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50"
                    :href="route('login')"
                >
                    Login
                </Link>
            </template>
            <template v-if="$page.props.user === null">
                <Link
                    class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50"
                    :href="route('register')"
                >
                    Register
                </Link>
            </template>
            <template v-if="$page.props.user !== null">
                <div
                    @click="handleMenuUserItem"
                    class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50"
                >
                    <div
                        class="h-9 w-9 flex-shrink-0"
                        v-if="
                            $page.props.user &&
                            $page.props.user.profile_photo_path !== null
                        "
                    >
                        <img
                            class="object-cover w-9 h-9 rounded-full flex-shrink-0"
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
                        @click="handleMenuUserItem"
                        type="button"
                        class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-200"
                    >
                        <UserIcon class="h-6 w-6" aria-hidden="true" />
                    </button>
                </div>
            </template>

            <template v-if="$page.props.user !== null">
                <button
                    @click="handleNotificationsSlideOver"
                    type="button"
                    class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-200"
                >
                    <span class="sr-only">View notifications</span>
                    <BellIcon class="h-6 w-6" aria-hidden="true" />
                </button>
            </template>

            <button
                @click="handlePrimaryMenuSlideOver"
                type="button"
                class="focus:outline-none cursor-pointer flex gap-2 items-center rounded-full px-1.5 py-1.5 hover:ring-2 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-200"
            >
                <span class="sr-only">View Menu</span>
                <Bars3Icon class="h-6 w-6" aria-hidden="true" />
            </button>
        </nav>
    </header>
</template>
