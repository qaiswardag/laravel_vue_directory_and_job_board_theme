<script setup>
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
    CheckIcon,
    MegaphoneIcon,
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
                                ?.logos.length !== 0
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
                            <ThumbnailSmallImageSlider
                                :images="
                                    $page.props.user.current_team
                                        .coverImagesWithLogos?.logos
                                "
                                imageSize="medium_path"
                                imageHeight="min-h-16 max-h-16"
                                imageWidth="w-16 min-w-16 max-w-16 object-cover"
                                :roundedFull="true"
                            ></ThumbnailSmallImageSlider>
                        </Link>
                        <Link
                            :href="
                                route(
                                    'teams.show',
                                    $page.props.user.current_team.id
                                )
                            "
                        >
                            <p
                                class="myPrimaryParagraph font-medium mt-2 text-center"
                            >
                                {{ $page.props.currentUserTeam?.name }}
                            </p>
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
                <Link
                    :href="
                        route('teams.show', $page.props.user.current_team.id)
                    "
                >
                    <p class="myPrimaryParagraph font-medium text-center">
                        {{ $page.props.currentUserTeam?.name }}
                    </p>
                </Link>
            </div>
        </template>

        <!-- User tag # start -->
        <div
            class="justify-between group flex items-center border-b border-gray-200 pb-2"
        >
            <UserTag
                :user="$page.props.user"
                :showTeamRole="true"
                :currentUserTeamRole="$page.props.currentUserTeamRole"
                :clickable="true"
            ></UserTag>

            <Link
                :href="route('user.profile.update')"
                class="focus:outline-none"
            >
                <button
                    type="button"
                    class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white hover:fill-white focus-visible:ring-0 text-myPrimaryDarkGrayColor"
                >
                    <span class="material-symbols-outlined"> edit </span>
                </button>
            </Link>
        </div>
        <!-- User tag # end -->

        <!--  -->
        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                !$page.props.user.current_team &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <div
                class="justify-between group flex items-center border-b border-gray-200 pb-2"
            >
                <Link
                    :href="route('user.teams.switch')"
                    class="myPrimaryTag hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer"
                    :class="{
                        'shadow-md': route().current('user.teams.switch'),
                    }"
                >
                    <div class="flex items-center gap-2">
                        <span class="myMediumIcon material-symbols-outlined">
                            check
                        </span>
                        Selected Company
                    </div>
                </Link>
            </div>
        </template>
        <!--  -->
        <div
            v-if="
                $page.props.user.all_teams.length === 0 &&
                $page.props.user.current_team === null &&
                $page.props.jetstream.hasTeamFeatures
            "
            class="border-b border-gray-200 pb-2"
        >
            <Link
                :href="route('teams.create.team')"
                class="myPrimaryTag hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer"
            >
                <div class="flex items-center gap-2">
                    <span class="myMediumIcon material-symbols-outlined">
                        check
                    </span>
                    <span class="font-medium"> Create Company </span>
                </div>
            </Link>
        </div>

        <SideBarLink
            :href="route('dashboard')"
            :active="route().current('dashboard') || route().current('home')"
        >
            <span class="material-symbols-outlined"> play_shapes </span>

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
                route().current('stripe.stores.edit.subscription') ||
                route().current('stripe.stores.create.subscription') ||
                route().current('stripe.payments.index')
            "
        >
            <span class="myMediumIcon material-symbols-outlined"> person </span>
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
                <span class="myMediumIcon material-symbols-outlined">
                    perm_media
                </span>
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
                    route().current('team.posts.index.trash') ||
                    route().current('team.posts.index.draft') ||
                    route().current('team.posts.create') ||
                    route().current('team.posts.post.edit') ||
                    route().current('team.posts.post.show')
                "
            >
                <span class="material-symbols-outlined"> article </span>
                <div class="flex flex-col gap-2">
                    <span class="block"> Posts </span>
                    <span class="block text-xs font-normal">
                        Manage or create new posts
                    </span>
                </div>
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
                    route().current('team.jobs.index.draft') ||
                    route().current('team.jobs.index.expired') ||
                    route().current('team.jobs.index.trash') ||
                    route().current('team.jobs.create') ||
                    route().current('team.jobs.job.edit') ||
                    route().current('team.jobs.job.show') ||
                    route().current('stripe.single.charge.job.create')
                "
            >
                <span class="myMediumIcon material-symbols-outlined">
                    work
                </span>
                <div class="flex flex-col gap-2">
                    <span class="block"> Jobs </span>
                    <span class="block text-xs font-normal">
                        Manage or create new jobs
                    </span>
                </div>
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
                    route().current('team.stores.index.draft') ||
                    route().current('team.stores.index.trash') ||
                    route().current('team.stores.create') ||
                    route().current('team.stores.store.edit') ||
                    route().current('team.stores.store.show')
                "
            >
                <span class="material-symbols-outlined"> local_mall </span>
                <div class="flex flex-col gap-2">
                    <span class="block"> Listings </span>
                    <span class="block text-xs font-normal">
                        Manage or create listings
                    </span>
                </div>
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
                <span class="myMediumIcon material-symbols-outlined">
                    settings
                </span>
                Company Settings
            </SideBarLink>
        </template>

        <SideBarLink
            :href="route('user.teams')"
            :active="
                route().current('user.teams') ||
                route().current('user.teams.switch') ||
                route().current('teams.create.team')
            "
        >
            <span class="material-symbols-outlined"> stacks </span> All
            Companies
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
            <span class="myMediumIcon material-symbols-outlined">
                shield_person
            </span>
            Superadmin
        </SideBarLink>
    </nav>
</template>
