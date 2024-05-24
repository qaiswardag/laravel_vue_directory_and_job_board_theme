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
    <Head title="Your Companies" />

    <MainLayout>
        <LoggedInLayout>
            <template #header> Your Companies </template>
            <template #description> Companies index page </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <div class="myPrimarySection">
                <div class="sm:grid sm:grid-cols-2 myPrimaryGap">
                    <div
                        class="relative group py-6 px-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryLinkColor cursor-pointer bg-white rounded-3xl border-2 border-white hover:border-2 hover:border-myPrimaryLinkColor"
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
                                    Switch Company
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
                    </div>

                    <div
                        class="relative group py-6 px-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryLinkColor cursor-pointer bg-white rounded-3xl border-2 border-white hover:border-2 hover:border-myPrimaryLinkColor"
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
                    </div>
                </div>
            </div>
        </LoggedInLayout>
    </MainLayout>
</template>
