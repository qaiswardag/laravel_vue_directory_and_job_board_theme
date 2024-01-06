<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
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
        label: "Company Settings",
        route: {
            name: "teams.show",
            parameters: [props.team.id],
        },
    },
];
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head title="Company Settings" />
            <template #header> Company Settings </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <div class="flex gap-2 justify-center items-center">
                <UserTag
                    v-if="$page.props.team.owner.id !== $page.props.user.id"
                    :user="$page.props.team.owner"
                    :showTeamRole="true"
                    :currentUserTeamRole="{ key: 'owner' }"
                    :showJobTitle="true"
                >
                </UserTag>
            </div>
            <!-- Company owner box # end -->
            <div class="myPrimarySection">
                <div
                    class="divide-y divide-gray-200 sm:grid sm:grid-cols-2 sm:gap-px sm:divide-y-0"
                >
                    <div
                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                    >
                        <div>
                            <span
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            >
                                <span class="material-symbols-outlined">
                                    edit
                                </span>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-normal">
                                <Link
                                    :href="
                                        route(
                                            'team.update.information',
                                            team.id
                                        )
                                    "
                                    class="focus:outline-none"
                                >
                                    <span
                                        class="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    Update Company
                                </Link>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Navigate to the page and discover a range of
                                settings crafted to suit your needs. Whether
                                it's personalizing your account details,
                                adjusting security preferences, or fine-tuning
                                company settings.
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                            aria-hidden="true"
                        >
                            <span class="material-symbols-outlined">
                                arrow_forward
                            </span>
                        </span>
                    </div>

                    <div
                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                    >
                        <div>
                            <span
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            >
                                <span class="material-symbols-outlined">
                                    group
                                </span>
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
                                    Members and Company Users
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
                            <span class="material-symbols-outlined">
                                arrow_forward
                            </span>
                        </span>
                    </div>
                    <!-- password -->
                    <div
                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                    >
                        <div>
                            <span
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            >
                                <span class="material-symbols-outlined">
                                    delete
                                </span>
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
                                    Delete Company
                                </Link>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Navigate to the page and discover a range of
                                settings crafted to suit your needs. Whether
                                it's personalizing your account details,
                                adjusting security preferences, or fine-tuning
                                company settings.
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                            aria-hidden="true"
                        >
                            <span class="material-symbols-outlined">
                                arrow_forward
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </LoggedInLayout>
    </MainLayout>
</template>
