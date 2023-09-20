<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import { router } from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import UserTag from "@/Components/Users/UserTag.vue";

const props = defineProps({
    team: Object,
    availableRoles: Array,
    permissions: Object,
});

const breadcrumbsLinks = [
    {
        label: "Team Settings",
        route: {
            name: "teams.show",
            parameters: [props.team.id],
        },
    },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Team Settings" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Team Settings</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <div class="flex gap-2 justify-center items-center">
            <UserTag
                v-if="$page.props.team.owner.id !== $page.props.user.id"
                :user="$page.props.team.owner"
                :showTeamRole="true"
                :currentUserTeamRole="{ key: 'owner' }"
            >
            </UserTag>
        </div>
        <!-- Team owner box # end -->
        <div class="myPrimarySection">
            <!-- team details # start -->
            <div class="my-12 p-6 border-2 border-green-300">
                <p class="font-bold text-center">Team name: {{ team.name }}</p>
                <div class="flex myPrimaryGap">
                    <div class="border-2 my-6 p-6 rounded border-red-300">
                        <p class="my-6">Show team logo here</p>
                        {{ team.coverImagesWithLogos.logos }}
                    </div>
                    <div class="border-2 my-6 p-6 rounded border-red-300">
                        <p class="my-6">Show team cover image here</p>
                        {{ team.coverImagesWithLogos.cover_images }}
                    </div>
                </div>
            </div>
            <!-- team details # start -->

            <div
                class="divide-y divide-gray-200 shadow sm:grid sm:grid-cols-2 sm:gap-px sm:divide-y-0"
            >
                <div
                    class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                >
                    <div>
                        <span
                            class="rounded bg-gray-50 text-myPrimaryBrandColor justify-center inline-flex p-3 ring-4 ring-white"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="text-myPrimaryBrandColor flex-shrink-0 h-6 w-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"
                                />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-lg font-normal">
                            <Link
                                :href="
                                    route('team.update.information', team.id)
                                "
                                class="focus:outline-none"
                            >
                                <span
                                    class="absolute inset-0"
                                    aria-hidden="true"
                                />
                                Update Team
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

                <div
                    class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                >
                    <div>
                        <span
                            class="rounded bg-gray-50 text-myPrimaryBrandColor justify-center inline-flex p-3 ring-4 ring-white"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="text-myPrimaryBrandColor flex-shrink-0 h-6 w-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"
                                />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-lg font-normal">
                            <Link
                                :href="route('team.members', team.id)"
                                class="focus:outline-none"
                            >
                                <span
                                    class="absolute inset-0"
                                    aria-hidden="true"
                                />
                                Team Members
                            </Link>
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Browser Sessions and Profile deletion
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
                <!-- password -->
                <div
                    class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                >
                    <div>
                        <span
                            class="rounded bg-gray-50 text-myPrimaryBrandColor justify-center inline-flex p-3 ring-4 ring-white"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="text-myPrimaryBrandColor flex-shrink-0 h-6 w-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-lg font-normal">
                            <Link
                                :href="route('team.delete', team.id)"
                                class="focus:outline-none"
                            >
                                <span
                                    class="absolute inset-0"
                                    aria-hidden="true"
                                />
                                Delete Team
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
</template>
