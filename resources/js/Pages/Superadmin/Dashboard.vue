<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import {
    AcademicCapIcon,
    BanknotesIcon,
    CheckBadgeIcon,
    ClockIcon,
    ReceiptRefundIcon,
    UsersIcon,
    BeakerIcon,
    Square3Stack3DIcon,
} from "@heroicons/vue/24/outline";
import UserTag from "@/Components/Users/UserTag.vue";

import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";

const breadcrumbsLinks = [
    {
        label: "Admin Dashboard",
        route: {
            name: "admin.dashboard",
            parameters: null,
        },
    },
];

const actions = [
    {
        title: "Users",
        href: "admin.users",
        icon: UsersIcon,
        iconForeground: "text-myPrimaryBrandColor",
        iconBackground: "bg-gray-50",
    },
    {
        title: "Page Builder Components",
        href: "admin.components",
        icon: Square3Stack3DIcon,
        iconForeground: "text-myPrimaryBrandColor",
        iconBackground: "bg-gray-50",
    },
];
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head title="Dashboard" />
            <template #header>
                <h2 class="myPrimaryMainPageHeader">Super Admininistrator</h2>
            </template>

            <template #description>Super Admininistrator Dashboard </template>

            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <div class="myPrimarySection">
                <div class="flex gap-2 justify-center items-center mb-8">
                    <UserTag
                        customClass="my-0"
                        :user="$page.props.user"
                        :showTeamRole="true"
                        :clickable="true"
                        :currentUserTeamRole="
                            $page.props.user.superadmin &&
                            $page.props.user.superadmin.role === 'admin'
                                ? { role: 'superadmin' }
                                : $page.props.user.superadmin
                        "
                    ></UserTag>
                </div>
                <div
                    class="divide-y divide-gray-200 sm:grid sm:grid-cols-2 sm:gap-px sm:divide-y-0"
                >
                    <div
                        v-for="(action, actionIdx) in actions"
                        :key="action.title"
                        :class="[
                            actionIdx === 0 ? '' : '',
                            actionIdx === 1 ? '' : '',
                            actionIdx === actions.length - 2 ? '' : '',
                            actionIdx === actions.length - 1 ? '' : '',
                            'relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer',
                        ]"
                    >
                        <div>
                            <span
                                :class="[
                                    action.iconBackground,
                                    action.iconForeground,
                                    'h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0',
                                ]"
                            >
                                <component
                                    :is="action.icon"
                                    class="myMediumIcon"
                                    aria-hidden="true"
                                />
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-normal">
                                <Link
                                    :href="route(action.href)"
                                    class="focus:outline-none"
                                >
                                    <!-- Extend touch target to entire panel -->
                                    <span
                                        class="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    {{ action.title }}
                                </Link>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Doloribus dolores nostrum quia qui natus officia
                                quod et dolorem. Sit repellendus qui ut at
                                blanditiis et quo et molestiae.
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                            aria-hidden="true"
                        >
                            <svg
                                class="h-6 w-6"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"
                                />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </LoggedInLayout>
    </MainLayout>
</template>
