<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import { router } from "@inertiajs/vue3";
import {
    CheckIcon,
    ExclamationCircleIcon,
    BellIcon,
    AdjustmentsHorizontalIcon,
    ArrowRightCircleIcon,
    ArrowRightIcon,
    Bars3BottomLeftIcon,
    Bars3Icon,
    CalendarIcon,
    ChartBarIcon,
    FolderIcon,
    HomeIcon,
    InboxIcon,
    UserIcon,
    UsersIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import {} from "@headlessui/vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
        required: true,
    },
});

const emit = defineEmits(["firstModalMenuButtonFunction"]);

// first button function
const firstButton = function () {
    emit("firstModalMenuButtonFunction");
};

const handleLogout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <Modal :show="show" @close="firstButton" maxWidth="md">
        <div
            class="px-4 mb-4 flex flex-row-reverse items-center justify-between"
        >
            <button
                @click="firstButton"
                aria-label="Close menu"
                class="-m-1 p-1"
                type="button"
                data-headlessui-state="open"
                tabindex="0"
            >
                <svg
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                    class="h-6 w-6 text-zinc-500 dark:text-zinc-400"
                >
                    <path
                        d="m17.25 6.75-10.5 10.5M6.75 6.75l10.5 10.5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    ></path>
                </svg>
            </button>
            <h2 class="text-sm font-normal text-zinc-600 dark:text-zinc-400">
                Menu
            </h2>
        </div>
        <div
            class="px-4 relative inline-block align-bottom text-left overflow-hidden transform transition-all sm:align-middle w-full pb-6"
        >
            <div class="myPrimaryParagraph flex flex-col gap-1">
                <p class="px-2 py-2 rounded-lg bg-gray-50">
                    Logged in as {{ $page.props.user.first_name }}
                    {{ $page.props.user.last_name }}
                </p>
                <p class="py-2 px-2 rounded-lg mt-2">Manage Team</p>

                <template
                    v-if="
                        $page.props.user.all_teams.length > 0 &&
                        $page.props.user.current_team &&
                        $page.props.jetstream.hasTeamFeatures
                    "
                >
                    <Link
                        :href="
                            route(
                                'teams.show',
                                $page.props.user.current_team.id
                            )
                        "
                    >
                        <div
                            class="group relative flex gap-x-6 rounded-lg px-4 py-2 items-center bg-gray-50 hover:underline"
                            :class="[
                                route().current('teams.show') ||
                                route().current('team.update.information') ||
                                route().current('team.members') ||
                                route().current('team.delete')
                                    ? 'bg-red-100 '
                                    : '',
                            ]"
                        >
                            <button
                                type="button"
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                            >
                                <AdjustmentsHorizontalIcon
                                    class="mySmallIcon"
                                />
                            </button>
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
                                ? 'bg-red-100'
                                : '',
                        ]"
                    >
                        <div>
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

                            <template
                                v-if="
                                    $page.props.user &&
                                    $page.props.user.profile_photo_path === null
                                "
                            >
                                <button
                                    type="button"
                                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                                >
                                    <UserIcon class="mySmallIcon" />
                                </button>
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
                        <button
                            type="button"
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                        >
                            <ArrowRightIcon class="mySmallIcon" />
                        </button>
                        <div>Log out</div>
                    </div>
                </form>
            </div>
            <slot></slot>
        </div>
    </Modal>
</template>
