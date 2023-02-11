<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";

defineProps({
    users: {
        required: true,
    },
});
</script>

<template>
    <LoggedInLayout title="Manage teæms">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Users</h2>
        </template>

        <template #description>
            <p>This is Super Admininistrator page for a list of users</p>
            <p>all users here: {{ users }}</p>
        </template>

        <!-- TABLE START -->
        <!-- TABLE START -->
        <!-- TABLE START -->
        <!-- TABLE START -->
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full py-2 align-middle px-1">
                <div
                    class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded"
                >
                    <table class="min-w-full divide-y divide-gray-300">
                        <caption class="bg-white">
                            <p class="myPrimaryParagraph my-4">Your posts</p>
                        </caption>
                        <thead class="bg-gray-50">
                            <tr class="divide-x divide-gray-300">
                                <th
                                    scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left myPrimaryParagraph text-gray-900 sm:pl-6"
                                >
                                    Thumbnail
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left myPrimaryParagraph text-gray-900"
                                >
                                    Title
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left myPrimaryParagraph text-gray-900"
                                >
                                    Status
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left myPrimaryParagraph text-gray-900"
                                >
                                    User id
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left myPrimaryParagraph text-gray-900"
                                >
                                    Owner of Team
                                </th>
                                <th
                                    scope="col"
                                    class="relative py-3.5 pl-3 pr-4 sm:pr-6 myPrimaryParagraph"
                                >
                                    Edit
                                    <span class="sr-only">Edit</span>
                                </th>
                                <th
                                    scope="col"
                                    class="relative py-3.5 pl-3 pr-4 sm:pr-6 myPrimaryParagraph"
                                >
                                    Delete
                                    <span class="sr-only">Delete</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <template v-if="users">
                                <tr
                                    class="divide-x divide-gray-200"
                                    v-for="user in users.data"
                                    :key="user.id"
                                >
                                    <td
                                        class="whitespace-nowrap py-4 pl-4 pr-3 sm:pl-6"
                                    >
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 flex-shrink-0"
                                            >
                                                <img
                                                    class="h-10 w-10 rounded-full object-cover"
                                                    src="https://emirateswoman.com/wp-content/uploads/2022/03/Arab-Fashion-Week-2022.png"
                                                    alt=""
                                                />
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-gray-900">
                                                    {{ user.first_name }}
                                                    {{ user.last_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td
                                        class="whitespace-nowrap px-3 py-4 text-gray-500"
                                    >
                                        <div class="text-gray-900">
                                            {{ user.first_name }}
                                            {{ user.last_name }}
                                        </div>
                                    </td>

                                    <td
                                        class="whitespace-nowrap px-3 py-4 text-gray-500"
                                    >
                                        <span
                                            :class="
                                                user.public
                                                    ? 'bg-green-100'
                                                    : 'bg-red-100 text-myErrorColor'
                                            "
                                            class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 text-green-800"
                                            >{{
                                                user.public
                                                    ? "Public"
                                                    : "Privat"
                                            }}</span
                                        >
                                    </td>

                                    <td
                                        class="whitespace-nowrap px-3 py-4 text-gray-500"
                                    >
                                        <div class="text-gray-900">
                                            {{ user.id }}
                                        </div>
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-3 py-4 text-gray-500"
                                    >
                                        <div class="text-gray-900">
                                            team name here
                                        </div>
                                    </td>

                                    <td
                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right sm:pr-6"
                                    >
                                        <a
                                            href="#"
                                            class="myPrimaryButtonNoBackground"
                                            >Edit<span class="sr-only"
                                                >, {{ user.id }}</span
                                            ></a
                                        >
                                    </td>
                                    <td
                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right sm:pr-6"
                                    >
                                        <a
                                            href="#"
                                            class="myPrimaryDeleteButtonNoBackground"
                                            >Delete<span class="sr-only"
                                                >, {{ user.id }}</span
                                            ></a
                                        >
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Pagination :links="users.links"></Pagination>
    </LoggedInLayout>
</template>
