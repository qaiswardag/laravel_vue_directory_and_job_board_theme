<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Dashboard from "@/Components/Dashboard/Dashboard.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";

const people = [
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    // More people...
];
</script>

<template>
    <LoggedInLayout title="Dashboard">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Dashboard</h2>
        </template>
        <Dashboard />

        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-base font-semibold leading-6 text-gray-900">
                        Users
                    </h1>
                    <p class="mt-2 text-sm text-gray-700">
                        A list of all the users in your account including their
                        name, title, email and role.
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <button
                        type="button"
                        class="block rounded-md bg-indigo-600 py-1.5 px-3 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                        Add user
                    </button>
                </div>
            </div>
            <div class="myTableContainer">
                <div class="myTableSubContainer">
                    <table class="myPrimaryTable">
                        <thead class="myPrimaryTableTHead">
                            <tr class="myPrimaryTableTr">
                                <th scope="col" class="myPrimaryTableTh">
                                    Name
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Title
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Email
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Role
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Name
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Title
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Email
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Role
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Name
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Title
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Email
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Role
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Name
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Title
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Email
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Role
                                </th>
                                <th
                                    scope="col"
                                    class="relative py-3.5 pl-3 pr-4 sm:pr-0"
                                >
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <TransitionGroup name="table">
                                <tr
                                    class="myPrimaryTableTBodyTr"
                                    v-for="person in people"
                                    :key="person.email"
                                >
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.name }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.title }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.email }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.role }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.name }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.title }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.email }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.role }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.name }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.title }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.email }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.role }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.name }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.title }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.email }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.role }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.name }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.title }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ person.email }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">Åøben</td>
                                    <td class="myPrimaryTableTBodyTd">
                                        <SubmitButton
                                            buttonText="Delete"
                                            :disabled="true"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                class="w-5 h-5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                                />
                                            </svg>
                                        </SubmitButton>
                                    </td>
                                </tr>
                            </TransitionGroup>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </LoggedInLayout>
</template>
