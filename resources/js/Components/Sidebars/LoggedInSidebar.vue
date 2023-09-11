<script setup>
import { router } from "@inertiajs/vue3";
import SideBarLink from "@/Components/MenuLinks/SideBarLink.vue";
import {
    FlagIcon,
    StarIcon,
    FolderIcon,
    ShoppingBagIcon,
    TagIcon,
    DocumentTextIcon,
    Squares2X2Icon,
    UserIcon,
    PhotoIcon,
    Cog6ToothIcon,
    Cog8ToothIcon,
    Square3Stack3DIcon,
    FingerPrintIcon,
} from "@heroicons/vue/24/outline";
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
                <span>Team:</span>

                <Link
                    :href="route('user.teams.switch')"
                    class="flex justify-center gap-2 items-center text-xs px-2 rounded-full ml-2 py-1.5 hover:ring-1 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-100"
                    :class="{
                        myPrimaryLink: route().current('user.teams.switch'),
                    }"
                >
                    <template
                        v-if="
                            $page.props.currentUserTeam?.logo_thumbnail !== null
                        "
                    >
                        <div class="h-8 w-8 flex-shrink-0">
                            <img
                                class="w-8 h-8 rounded-full object-cover cursor-pointer"
                                :src="`/storage/uploads/${$page.props.currentUserTeam?.logo_thumbnail}`"
                                alt="Logo"
                            />
                        </div>
                    </template>
                    {{ $page.props.currentUserTeam?.name }}
                </Link>
            </p>
            <p
                class="text-myPrimaryDarkGrayColor justify-between group flex items-center px-2 py-2 text-xs font-normal border-b border-gray-200"
            >
                <span>Role:</span>
                <Link
                    :href="route('user.teams.switch')"
                    class="text-xs px-2 rounded-full ml-2 py-1.5 hover:ring-1 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-100"
                    :class="{
                        myPrimaryLink: route().current('user.teams.switch'),
                    }"
                >
                    {{ $page.props.currentUserTeamRole?.name }}
                </Link>
            </p>
        </template>

        <!--  -->
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
                <span>Team:</span>

                <Link
                    :href="route('user.teams.switch')"
                    class="flex justify-center gap-2 items-center text-xs px-2 rounded-full ml-2 py-1.5 hover:ring-1 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-100"
                    :class="{
                        myPrimaryLink: route().current('user.teams.switch'),
                    }"
                >
                    No Team selected
                </Link>
            </p>
        </template>
        <!--  -->
        <template
            v-if="
                $page.props.user.all_teams.length === 0 &&
                $page.props.user.current_team === null &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <p
                class="text-myPrimaryDarkGrayColor justify-between group flex items-center px-2 py-2 text-xs font-normal border-b border-gray-200"
            >
                <span>Team:</span>

                <Link
                    :href="route('user.teams.create')"
                    class="flex justify-center gap-2 items-center text-xs px-2 rounded-full ml-2 py-1.5 hover:ring-1 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-100"
                    :class="{
                        myPrimaryLink: route().current('user.teams.create'),
                    }"
                >
                    Create a Team
                </Link>
            </p>
        </template>

        <SideBarLink
            :href="route('dashboard')"
            :active="route().current('dashboard')"
        >
            <Squares2X2Icon
                class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
            ></Squares2X2Icon>

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
            <UserIcon
                class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
            ></UserIcon>
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
                :href="
                    route(
                        'media.index',
                        $page.props.user.current_team.reference_id
                    )
                "
                :active="route().current('media.index')"
            >
                <PhotoIcon
                    class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
                ></PhotoIcon>
                Media Team
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
                        'team.posts.index',
                        $page.props.user.current_team.reference_id
                    )
                "
                :active="
                    route().current('team.posts.index') ||
                    route().current('team.posts.create') ||
                    route().current('team.posts.post.edit')
                "
            >
                <DocumentTextIcon
                    class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
                ></DocumentTextIcon>

                Blog Team
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
                        'team.jobs.index',
                        $page.props.user.current_team.reference_id
                    )
                "
                :active="
                    route().current('team.jobs.index') ||
                    route().current('team.jobs.create') ||
                    route().current('team.jobs.job.edit')
                "
            >
                <TagIcon
                    class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
                ></TagIcon>

                Jobs Team
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
                        'team.stores.index',
                        $page.props.user.current_team.reference_id
                    )
                "
                :active="
                    route().current('team.stores.index') ||
                    route().current('team.stores.create') ||
                    route().current('team.stores.store.edit')
                "
            >
                <ShoppingBagIcon
                    class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
                ></ShoppingBagIcon>
                Stores Team
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
                        'teams.show',
                        $page.props.user.current_team.reference_id
                    )
                "
                :active="
                    route().current('teams.show') ||
                    route().current('team.update.information') ||
                    route().current('team.members') ||
                    route().current('team.delete')
                "
            >
                <Cog6ToothIcon
                    class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
                ></Cog6ToothIcon>
                Settings Team
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
            <Square3Stack3DIcon
                class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
            ></Square3Stack3DIcon>
            All Teams
        </SideBarLink>
        <SideBarLink
            v-if="$page.props.user.superadmin !== null"
            :href="route('admin.dashboard')"
            :active="
                route().current('admin.dashboard') ||
                route().current('admin.users') ||
                route().current('admin.components') ||
                route().current('admin.components.component.create') ||
                route().current('admin.components.component.edit')
            "
        >
            <FingerPrintIcon
                class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
            ></FingerPrintIcon>
            Superadmin
        </SideBarLink>
    </nav>
</template>
