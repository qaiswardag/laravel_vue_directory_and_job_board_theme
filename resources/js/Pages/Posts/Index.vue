<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import ActionSection from "@/Components/ActionSection.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";

defineProps({
    posts: {
        required: true,
    },
});
</script>

<template>
    <LoggedInLayout title="Manage Posts">
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Manage Posts</h2>
        </template>

        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Users</h1>
                <p class="mt-2 text-gray-700">
                    A list of all the users in your account including their
                    name, title, email and role.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <Link
                    class="myPrimaryButton"
                    type="button"
                    :href="route('posts.create')"
                >
                    Add Post
                </Link>
            </div>
        </div>

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
                                    Post by user id
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left myPrimaryParagraph text-gray-900"
                                >
                                    Team id
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
                            <tr
                                class="divide-x divide-gray-200"
                                v-for="post in posts.data"
                                :key="post.id"
                            >
                                <td
                                    class="whitespace-nowrap py-4 pl-4 pr-3 sm:pl-6"
                                >
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <img
                                                class="h-10 w-10 rounded-full object-cover"
                                                src="https://emirateswoman.com/wp-content/uploads/2022/03/Arab-Fashion-Week-2022.png"
                                                alt=""
                                            />
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-gray-900">
                                                {{ post.title }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td
                                    class="whitespace-nowrap px-3 py-4 text-gray-500"
                                >
                                    <div class="text-gray-900">
                                        {{ post.title }}
                                    </div>
                                </td>

                                <td
                                    class="whitespace-nowrap px-3 py-4 text-gray-500"
                                >
                                    <span
                                        :class="
                                            post.published
                                                ? 'bg-green-100'
                                                : 'bg-red-100 text-myErrorColor'
                                        "
                                        class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 text-green-800"
                                        >{{
                                            post.published
                                                ? "Published"
                                                : "Privat"
                                        }}</span
                                    >
                                </td>

                                <td
                                    class="whitespace-nowrap px-3 py-4 text-gray-500"
                                >
                                    <div class="text-gray-900">
                                        user name here
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
                                            >, {{ post.title }}</span
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
                                            >, {{ post.title }}</span
                                        ></a
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Pagination :links="posts.links"></Pagination>
    </LoggedInLayout>
</template>
