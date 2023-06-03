<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Dashboard from "@/Components/Dashboard/Dashboard.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import { MinusSmallIcon, PlusSmallIcon } from "@heroicons/vue/24/outline";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import EmptySectionBorder from "@/Components/Sections/EmptySectionBorder.vue";

import { ref } from "vue";

const props = defineProps({
    user: {
        required: true,
    },
    currentUserTeamRole: {
        required: true,
    },
    currentUserTeam: {
        required: true,
    },
});

const currentUserDetails = ref(false);
</script>

<template>
    <LoggedInLayout>
        <Head title="Dashboard" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Dashboard</h2>
        </template>
        <Dashboard />

        <FullWidthElement :descriptionArea="true" class="bg-red-50">
            <template #title>Latest Users and Posts</template>
            <template #description>
                Retrieve details about the logged-in user and team insights for
                current user. Discover insights about the logged-in user and the
                team.
            </template>
            <template #content>
                <div class="grid grid-cols-1 lg:gap-16 gap-24 lg:grid-cols-12">
                    <div class="lg:col-span-6">
                        <p class="mb-24">Latest Users</p>
                        <div
                            class="p-2 rounded-md min-h-[4rem] max-h-[18rem] flex flex-col w-full border border-myPrimaryLightGrayColor divide-y divide-gray-200 bg-white"
                        >
                            <div class="hover:bg-white px-2">
                                <div class="rounded">
                                    <!-- start photo -->
                                    <div class="flex items-center gap-2 my-4">
                                        <!--v-if-->
                                        <div
                                            class="w-12 h-12 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
                                        >
                                            J D
                                        </div>
                                        <span
                                            class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                            ><p class="font-medium">John Doe</p>
                                            <p>Team Role: Owner</p></span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-6">Latest Posts</div>
                </div>
            </template>
        </FullWidthElement>
        <EmptySectionBorder></EmptySectionBorder>
        <FullWidthElement :descriptionArea="true">
            <template #title>Latest images uploaded by your team</template>
            <template #description>
                You are currently viewing the latest images uploaded by your
                team. These images are sorted by date, with the most recent ones
                displayed first.
            </template>
            <template #content>
                <div class="grid grid-cols-1 lg:gap-16 gap-24 lg:grid-cols-1">
                    <ul
                        role="list"
                        class="grid grid-cols-2 myPrimaryGap sm:grid-cols-4 lg:grid-cols-6"
                    >
                        <li
                            v-for="file in false && latestMedia"
                            :key="file.id"
                            class="border border-myPrimaryLightGrayColor rounded-lg p-1 bg-gray-50"
                        >
                            <div
                                class="group aspect-w-10 aspect-h-7 block w-full overflow-hidden rounded-lg bg-gray-100 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100"
                            >
                                <img
                                    :src="`/storage/uploads/${file.medium_path}`"
                                    alt=""
                                    class="w-full pointer-events-none object-cover group-hover:opacity-75"
                                />
                            </div>
                        </li>
                    </ul>
                </div>
            </template>
        </FullWidthElement>
        <FullWidthElement :descriptionArea="true">
            <template #title
                >Details about the logged-in user and team insights</template
            >
            <template #description>
                Retrieve details about the logged-in user and team insights for
                current user. Discover insights about the logged-in user and the
                team.
            </template>
            <template #content>
                <div class="grid grid-cols-1 lg:gap-16 gap-24 lg:grid-cols-1">
                    <dl
                        class="divide-y divide-myPrimaryMediumGrayColor lg:col-span-4"
                    >
                        <Disclosure as="div" v-slot="{ currentUserDetails }">
                            <dt>
                                <DisclosureButton
                                    class="py-6 flex w-full items-center justify-between text-left myPrimaryParagraph"
                                >
                                    <span class="myPrimaryParagraph font-medium"
                                        >Details about the logged-in user and
                                        team insights
                                    </span>
                                    <span class="ml-6 flex h-7 items-center">
                                        <PlusSmallIcon
                                            v-if="!currentUserDetails"
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                        <MinusSmallIcon
                                            v-else
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                    </span>
                                </DisclosureButton>
                            </dt>
                            <DisclosurePanel as="dd" class="mt-2 pr-12 pb-16">
                                <div
                                    class="overflow-hidden bg-white px-8 rounded-lg"
                                >
                                    <div
                                        class="mb-8 mt-12 pb-4 whitespace-pre-line"
                                    >
                                        <p class="myTertiaryHeader">User:</p>
                                        <br />
                                        {{ user }}
                                    </div>
                                    <div class="my-8 pb-16 whitespace-pre-line">
                                        <p class="myTertiaryHeader">
                                            currentUserTeamRole from shared
                                            Data:
                                        </p>
                                        <br />
                                        {{ currentUserTeamRole }}
                                    </div>
                                    <div class="my-8 pb-16 whitespace-pre-line">
                                        <p class="myTertiaryHeader">
                                            currentUserTeam from shared Data:
                                        </p>
                                        <br />
                                        {{ currentUserTeam }}
                                    </div>
                                </div>
                            </DisclosurePanel>
                        </Disclosure>
                    </dl>
                </div>
            </template>
        </FullWidthElement>
    </LoggedInLayout>
</template>
