<script setup>
import { router } from "@inertiajs/vue3";
import SideBarLink from "@/Components/MenuLinks/SideBarLink.vue";
</script>

<template>
    <nav class="flex-1 space-y-2 px-2 pb-4" aria-label="menu">
        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                $page.props.user.current_team &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <p
                class="text-myPrimaryDarkGrayColor justify-between group flex items-center px-2 py-2 text-xs font-normal border-b border-gray-200"
            >
                <span>Current Team:</span>
                <Link
                    :href="
                        route(
                            'user.teams.switch',
                            $page.props.user.current_team
                        )
                    "
                    class="text-xs px-2 rounded-full ml-2 py-1.5 hover:ring-1 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-100"
                    :class="{
                        myPrimaryLink: route().current('user.teams.switch'),
                    }"
                >
                    {{ $page.props.currentUserTeam?.name }}
                </Link>
            </p>
            <p
                class="text-myPrimaryDarkGrayColor justify-between group flex items-center px-2 py-2 text-xs font-normal border-b border-gray-200"
            >
                <span>Current Role:</span>
                <Link
                    :href="
                        route(
                            'user.teams.switch',
                            $page.props.user.current_team
                        )
                    "
                    class="text-xs px-2 rounded-full ml-2 py-1.5 hover:ring-1 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-100"
                    :class="{
                        myPrimaryLink: route().current('user.teams.switch'),
                    }"
                >
                    {{ $page.props.currentUserTeamRole?.name }}
                </Link>
            </p>
        </template>

        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                !$page.props.user.current_team &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <p
                class="text-myPrimaryDarkGrayColor justify-between group flex items-center px-2 py-2 text-xs font-normal border-b border-gray-200"
            >
                <span class="self-start block">Current Team:</span>

                <Link
                    :href="
                        route(
                            'user.teams.switch',
                            $page.props.user.current_team
                        )
                    "
                    class="text-xs px-2 rounded-full ml-2 py-1.5 hover:ring-1 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-100"
                    :class="{
                        myPrimaryLink: route().current('user.teams.switch'),
                    }"
                >
                    No Team Selected
                </Link>
            </p>
            <p
                class="text-myPrimaryDarkGrayColor justify-between group flex items-center px-2 py-2 text-xs font-normal border-b border-gray-200"
            >
                <span class="self-start block">Current Team Role:</span>

                <Link
                    :href="
                        route(
                            'user.teams.switch',
                            $page.props.user.current_team
                        )
                    "
                    class="text-xs px-2 rounded-full ml-2 py-1.5 hover:ring-1 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-100"
                    :class="{
                        myPrimaryLink: route().current('user.teams.switch'),
                    }"
                >
                    None
                </Link>
            </p>
        </template>
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
                class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"
                />
            </svg>
            Dashboard
        </SideBarLink>
        <SideBarLink
            :href="route('profile.show')"
            :active="
                route().current('profile.show') ||
                route().current('user.profile.update') ||
                route().current('user.profile.password') ||
                route().current('user.profile.security')
            "
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                />
            </svg>
            Your Profile
        </SideBarLink>

        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                $page.props.user.current_team &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <SideBarLink
                :href="route('media.index', $page.props.user.current_team.id)"
                :active="route().current('media.index')"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                    />
                </svg>
                Media
            </SideBarLink>
        </template>

        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                $page.props.user.current_team &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <SideBarLink
                :href="
                    route(
                        'overview.posts.index',
                        $page.props.user.current_team.id
                    )
                "
                :active="
                    route().current('overview.posts.index') ||
                    route().current('overview.posts.create')
                "
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"
                    />
                </svg>
                Posts
            </SideBarLink>
        </template>
        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                $page.props.user.current_team &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <SideBarLink
                :href="route('teams.show', $page.props.user.current_team)"
                :active="
                    route().current('teams.show') ||
                    route().current('team.update.information') ||
                    route().current('team.members') ||
                    route().current('team.delete')
                "
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"
                    />
                </svg>
                Team Settings
            </SideBarLink>
        </template>

        <SideBarLink
            :href="route('user.teams')"
            :active="
                route().current('user.teams') ||
                route().current('user.teams.switch') ||
                route().current('user.teams.create')
            "
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"
                />
            </svg>
            Your Teams
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
                class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"
                />
            </svg>
            Docs
        </SideBarLink>
        <SideBarLink
            :href="route('admin.dashboard')"
            :active="
                route().current('admin.dashboard') ||
                route().current('admin.users')
            "
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"
                />
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
            </svg>
            Superadmin
        </SideBarLink>

        <a
            href="/log-viewer"
            target="_blank"
            class="myPrimaryParagraph pt-8 pl-8 italic block"
        >
            Log Viewer
        </a>
    </nav>
</template>
