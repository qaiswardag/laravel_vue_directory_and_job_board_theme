<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/MarkComponents/ApplicationMark.vue";
import Dropdown from "@/Components/Dropdowns/Dropdown.vue";
import DropdownLink from "@/Components/Dropdowns/DropdownLink.vue";
import NavLink from "@/Components/Navbars/NavLink.vue";
import ResponsiveNavLink from "@/Components/Navbars/ResponsiveNavLink.vue";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
</script>

<template>
    <nav class="border-b border-gray-100 bg-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('home')">
                            <ApplicationMark class="block h-9 w-auto" />
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <NavLink
                            :href="route('home')"
                            :active="route().current('home')"
                        >
                            Home
                        </NavLink>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <NavLink
                            :href="route('login')"
                            :active="route().current('login')"
                        >
                            Login
                        </NavLink>
                        <NavLink
                            :href="route('register')"
                            :active="route().current('register')"
                        >
                            Register
                        </NavLink>
                    </div>
                </div>

                <div
                    class="hidden sm:flex sm:items-center sm:ml-6 myPrimaryGap"
                >
                    <div class="ml-3 relative">
                        <!-- Teams Dropdown -->
                        <Dropdown v-if="true" align="right" width="60">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-myPrimaryNormalColor bg-white hover:bg-gray-50 hover:text-myPrimaryNormalColor focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition"
                                    >
                                        Current team name

                                        <svg
                                            class="ml-2 -mr-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <template
                                        v-if="
                                            $page.props.jetstream
                                                .hasTeamFeatures
                                        "
                                    >
                                        <div
                                            class="block px-4 py-2 text-xs text-myPrimaryLightTextColor"
                                        >
                                            Manage Team
                                        </div>

                                        <DropdownLink
                                            v-if="true"
                                            :href="route('teams.create')"
                                            :active="
                                                route().current('teams.create')
                                            "
                                        >
                                            Create New Team
                                        </DropdownLink>

                                        <div class="border-t border-gray-100" />

                                        <!-- Team Switcher -->
                                        <div
                                            class="block px-4 py-2 text-xs text-myPrimaryLightTextColor"
                                        >
                                            Switch Teams
                                        </div>

                                        <DropdownLink
                                            v-if="true"
                                            :href="route('teams.create')"
                                            :active="
                                                route().current('teams.create')
                                            "
                                        >
                                            Something here
                                        </DropdownLink>
                                    </template>
                                </div>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="ml-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    v-if="false"
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                                >
                                    <img
                                        class="h-8 w-8 rounded-full object-cover"
                                        :src="
                                            $page.props.user.profile_photo_url
                                        "
                                        :alt="$page.props.user.name"
                                    />
                                </button>

                                <span v-else class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-myPrimaryNormalColor bg-white hover:text-myPrimaryNormalColor focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition"
                                    >
                                        Get Startet Today

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="ml-2 -mr-0.5 h-4 w-4"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <!-- Account Management -->
                                <div
                                    class="block px-4 py-2 text-xs text-myPrimaryLightTextColor"
                                >
                                    Sign in or register
                                </div>

                                <DropdownLink
                                    :href="route('login')"
                                    :active="route().current('login')"
                                >
                                    Login
                                </DropdownLink>

                                <DropdownLink
                                    :href="route('register')"
                                    :active="route().current('register')"
                                >
                                    Register
                                </DropdownLink>

                                <DropdownLink
                                    :href="route('privacyPolicy')"
                                    :active="route().current('privacyPolicy')"
                                >
                                    Privacy Policy
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                    <NavLink
                        :href="route('login')"
                        :active="route().current('login')"
                    >
                        Login
                    </NavLink>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button
                        class="inline-flex items-center justify-center p-2 rounded-md text-myPrimaryLightTextColor hover:text-myPrimaryNormalColor hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-myPrimaryNormalColor transition"
                        @click="
                            showingNavigationDropdown =
                                !showingNavigationDropdown
                        "
                    >
                        <svg
                            class="h-6 w-6"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
            :class="{
                block: showingNavigationDropdown,
                hidden: !showingNavigationDropdown,
            }"
            class="sm:hidden"
        >
            <div class="pt-2 pb-3 space-y-1">
                <ResponsiveNavLink
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                >
                    Dashboard
                </ResponsiveNavLink>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div v-if="true" class="shrink-0 mr-3">User image here</div>

                    <div>
                        <div
                            class="font-medium text-base text-myPrimaryNormalColor"
                        >
                            User name here
                        </div>
                        <div
                            class="font-medium text-sm text-myPrimaryNormalColor"
                        >
                            User name here
                        </div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink
                        :href="route('profile.show')"
                        :active="route().current('profile.show')"
                    >
                        Profile
                    </ResponsiveNavLink>

                    <!-- Team Management -->
                    <template v-if="true">
                        <div class="border-t border-gray-200" />

                        <div
                            class="block px-4 py-2 text-xs text-myPrimaryLightTextColor"
                        >
                            Manage Team
                        </div>

                        <!-- Team Settings -->

                        <ResponsiveNavLink
                            v-if="true"
                            :href="route('teams.create')"
                            :active="route().current('teams.create')"
                        >
                            Create New Team
                        </ResponsiveNavLink>

                        <div class="border-t border-gray-200" />

                        <!-- Team Switcher -->
                        <div
                            class="block px-4 py-2 text-xs text-myPrimaryLightTextColor"
                        >
                            Switch Teams
                        </div>

                        <ResponsiveNavLink
                            v-if="true"
                            :href="route('teams.create')"
                            :active="route().current('teams.create')"
                        >
                            Create New Team
                        </ResponsiveNavLink>
                    </template>
                </div>
            </div>
        </div>
    </nav>
</template>
