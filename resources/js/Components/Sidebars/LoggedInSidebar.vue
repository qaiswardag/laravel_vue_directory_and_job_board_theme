<script setup>
import { router } from "@inertiajs/vue3";
import SideBarLink from "@/Components/MenuLinks/SideBarLink.vue";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import UserTag from "@/Components/Users/UserTag.vue";

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
            <div
                class="flex items-center justify-center px-2 py-2"
                v-if="$page.props.user.current_team.coverImagesWithLogos"
            >
                <div
                    v-if="
                        Array.isArray(
                            $page.props.user.current_team.coverImagesWithLogos
                                .logos
                        )
                    "
                >
                    <template
                        v-if="
                            $page.props.user.current_team.coverImagesWithLogos
                                ?.logos
                        "
                    >
                        <Link :href="route('user.teams.switch')">
                            <ThumbnailSmallImageSlider
                                :images="
                                    $page.props.user.current_team
                                        .coverImagesWithLogos?.logos
                                "
                                imageSize="medium_path"
                                imageHeight="h-16"
                                imageWidth="w-16"
                                :roundedFull="true"
                            ></ThumbnailSmallImageSlider>
                        </Link>
                    </template>
                </div>
            </div>
            <div
                v-if="
                    $page.props.user.current_team.coverImagesWithLogos?.logos
                        ?.length === 0
                "
                class="flex items-center justify-center px-2 py-2"
            >
                <Link :href="route('user.teams.switch')">
                    <p class="myPrimaryParagraph font-medium">
                        {{ $page.props.currentUserTeam?.name }}
                    </p>
                </Link>
            </div>

            <div
                class="justify-between group flex items-center px-2 border-b border-gray-200 pb-2"
            >
                <Link :href="route('profile.show')">
                    <UserTag
                        :user="$page.props.user"
                        :showTeamRole="true"
                        :currentUserTeamRole="$page.props.currentUserTeamRole"
                        :clickable="true"
                    ></UserTag>
                </Link>
            </div>
        </template>

        <div
            v-if="!$page.props.user.current_team"
            class="justify-between group flex items-center px-2 border-b border-gray-200 pb-2"
        >
            <Link :href="route('user.show')">
                <UserTag
                    :user="$page.props.user"
                    :showTeamRole="true"
                    :currentUserTeamRole="$page.props.currentUserTeamRole"
                    :clickable="true"
                ></UserTag>
            </Link>
        </div>

        <!--  -->
        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                !$page.props.user.current_team &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <div
                class="justify-between group flex items-center px-2 border-b border-gray-200 pb-2"
            >
                <Link
                    :href="route('user.teams.switch')"
                    class="myPrimaryTag hover:bg-myPrimaryLinkColor hover:text-white"
                    :class="{
                        'shadow-md': route().current('user.teams.switch'),
                    }"
                >
                    Selected Team
                </Link>
            </div>
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
                <Link
                    :href="route('user.teams.create')"
                    class="myPrimaryTag hover:bg-myPrimaryLinkColor hover:text-white"
                >
                    Create a Team
                </Link>
            </p>
        </template>

        <SideBarLink
            :href="route('dashboard')"
            :active="route().current('dashboard')"
        >
            <Squares2X2Icon class="myMediumIcon"></Squares2X2Icon>

            Dashboard
        </SideBarLink>
        <SideBarLink
            :href="route('profile.show')"
            :active="
                route().current('profile.show') ||
                route().current('user.profile.update') ||
                route().current('user.profile.password') ||
                route().current('user.profile.security') ||
                route().current('user.profile.security') ||
                route().current('stripe.payment.subscription.index') ||
                route().current('stripe.payments.index')
            "
        >
            <UserIcon class="myMediumIcon"></UserIcon>
            Your Profile
        </SideBarLink>

        <div class="px-2 pt-4 rounded-none">
            <p
                class="myPrimaryParagraph text-myPrimaryMediumGrayColor italic text-xs pt-2 border-t"
            >
                Company
            </p>
        </div>
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
                <PhotoIcon class="myMediumIcon"></PhotoIcon>
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
                    route('team.posts.index', $page.props.user.current_team.id)
                "
                :active="
                    route().current('team.posts.index') ||
                    route().current('team.posts.create') ||
                    route().current('team.posts.post.edit') ||
                    route().current('team.posts.post.show')
                "
            >
                <DocumentTextIcon class="myMediumIcon"></DocumentTextIcon>

                Blog
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
                    route('team.jobs.index', $page.props.user.current_team.id)
                "
                :active="
                    route().current('team.jobs.index') ||
                    route().current('team.jobs.create') ||
                    route().current('team.jobs.job.edit') ||
                    route().current('team.jobs.job.show')
                "
            >
                <TagIcon class="myMediumIcon"></TagIcon>

                Jobs
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
                    route('team.stores.index', $page.props.user.current_team.id)
                "
                :active="
                    route().current('team.stores.index') ||
                    route().current('team.stores.create') ||
                    route().current('team.stores.store.edit') ||
                    route().current('team.stores.store.show')
                "
            >
                <ShoppingBagIcon class="myMediumIcon"></ShoppingBagIcon>
                Stores
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
                :href="route('teams.show', $page.props.user.current_team.id)"
                :active="
                    route().current('teams.show') ||
                    route().current('team.update.information') ||
                    route().current('team.members') ||
                    route().current('team.delete')
                "
            >
                <Cog6ToothIcon class="myMediumIcon"></Cog6ToothIcon>
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
            <Square3Stack3DIcon class="myMediumIcon"></Square3Stack3DIcon>
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
            <FingerPrintIcon class="myMediumIcon"></FingerPrintIcon>
            Superadmin
        </SideBarLink>
    </nav>
</template>
