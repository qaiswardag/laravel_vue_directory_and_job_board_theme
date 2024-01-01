<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";

const breadcrumbsLinks = [
    {
        label: "Your Teams",
        route: {
            name: "user.teams",
            parameters: null,
        },
    },
];
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head title="Your Companies" />
            <template #header> Your Companies </template>
            <template #description> Companies index page </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

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
                                    compare_arrows
                                </span>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-normal">
                                <Link
                                    :href="route('user.teams.switch')"
                                    class="focus:outline-none"
                                >
                                    <span
                                        class="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    Switch Teams
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
                                    add
                                </span>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-normal">
                                <Link
                                    :href="route('teams.create.team')"
                                    class="focus:outline-none"
                                >
                                    <span
                                        class="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    Create Company
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
                </div>
            </div>
        </LoggedInLayout>
    </MainLayout>
</template>
