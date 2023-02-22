<script setup>
import { router } from "@inertiajs/vue3";
import SideBarLink from "@/Components/MenuLinks/SideBarLink.vue";
import { UsersIcon } from "@heroicons/vue/24/outline";

const isActiveRoute = function (subRoutes, current) {
    let result = subRoutes?.includes(current);

    return result;
};

console.log(
    "er:",
    isActiveRoute(
        ["overview.posts.index", "overview.posts.create"],
        "overview.posts.create"
    )
);

const actions = [
    {
        title: "Dashboard",
        href: "dashboard",
        subRoutes: ["dashboard"],
        icon: UsersIcon,
    },
    {
        title: " Your Profile",
        href: "profile.show",
        subRoutes: ["profile.show"],
        icon: UsersIcon,
    },
    {
        title: " Posts",
        href: "overview.posts.index",
        subRoutes: ["overview.posts.index", "overview.posts.create"],
        icon: UsersIcon,
    },
    {
        title: "Team Settings",
        href: "teams.show",
        parameter: "$page.props.user.current_team",
        subRoutes: ["teams.show"],
        icon: UsersIcon,
    },
];
</script>
<template>
    <nav class="flex-1 space-y-1 px-2 pb-4" aria-label="menu">
        <div
            class="text-myPrimaryDarkGrayColor group flex items-center px-2 py-2 text-xs font-normal pt-2 pb-2 border-b border-gray-200"
        >
            Navigation
        </div>

        <template v-for="action in actions" :key="action.href">
            <SideBarLink
                :href="
                    route(
                        action.href,
                        action.parameter ? action.parameter : null
                    )
                "
                :class="{
                    'bg-gray-100': isActiveRoute(
                        action.subRoutes,
                        route().current()
                    ),
                }"
            >
                <component
                    :is="action.icon"
                    class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
                    aria-hidden="true"
                />

                {{ action.title }}
            </SideBarLink>
        </template>
    </nav>
</template>
