<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import { router, Head } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import { vueFetch } from "use-lightweight-fetch";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";

// test using costum npm package
// test using costum npm package
// test using costum npm package
// test using costum npm package
// use lightweight fetch
const pathBackendUsers = "/api/docs-users";
const {
    handleData: handleBackendDataUsers,
    fetchedData: fetchedBackendDataUsers,
    isError: isErrorBackendDataUsers,
    validationProperties,
    isLoading: isLoadingBackendDataUsers,
    isSuccess: isSuccessBackendDataUsers,
} = vueFetch();

const getBackenUsers = async function () {
    handleBackendDataUsers(
        pathBackendUsers,
        {},
        {
            loading: true,
            additionalCallTime: 2000,
            abortTimeoutTime: 8000,
        }
    );
};

const pathUsers = "https://jsonplaceholder.typicode.com/users";
const {
    handleData: handleDataUsers,
    fetchedData: fetchedDataUsers,
    isError: isErrorDataUsers,
    isLoading: isLoadingDataUsers,
    isSuccess: isSuccessDataUsers,
} = vueFetch();

onMounted(() => {
    handleDataUsers(
        pathUsers,
        {},
        {
            loading: true,
            additionalCallTime: 200,
            abortTimeoutTime: 8000,
        }
    );
});

// submit button test - start
// submit button test - start
// submit button test - start
// submit button test - start
//
const runMe = ref(false);
//
//
const TestSubmitButtonTwice = function () {
    console.log("you clicked me");
    runMe.value = true;

    // set timer
    setTimeout(() => {
        runMe.value = false;
    }, 1000);
};
// submit button test - end
// submit button test - end
// submit button test - end
// submit button test - end

// test using costum npm package
// test using costum npm package
// test using costum npm package
// test using costum npm package
</script>

<template>
    <LoggedInLayout title="Docs">
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Docs</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 px-3">
                <!-- Test start-->
                <div
                    class="mt-4 mb-8 p-4 border-2 border-emerald-500 rounded-xl"
                >
                    <p class="myPrimaryParagraph font-semibold mb-2">
                        Link button
                    </p>
                    <p class="myPrimaryParagraph">
                        Use Inertia Link as POST request and render the LINK as
                        a button, send data in request and add CSRF token.
                    </p>

                    <p class="myPrimaryLink my-2">
                        <Link
                            href="/logout"
                            method="POST"
                            as="button"
                            :data="{ foooooo: 'bar' }"
                            :headers="{
                                _token: this.$page.props.csrf_token,
                            }"
                        >
                            Log out
                        </Link>
                    </p>
                    <p class="myPrimaryLink my-2">
                        <Link
                            :href="route('home')"
                            as="button"
                            :data="{ fooooo: 'bar' }"
                            :headers="{
                                _token: this.$page.props.csrf_token,
                            }"
                        >
                            Home - go to named route
                        </Link>
                    </p>
                </div>
                <!-- Test end-->

                <!-- Test start-->
                <div
                    class="mt-4 mb-8 p-4 border-2 border-emerald-500 rounded-xl"
                >
                    <p class="myPrimaryParagraph font-semibold mb-2">
                        Scroll Position
                        <br />
                        For example when clicking on a like button.
                    </p>
                    <p class="myPrimaryParagraph">
                        Using the: preserve-scroll attribute
                    </p>
                </div>
                <!-- Test end-->

                <!-- Test start-->
                <div
                    class="mt-4 mb-8 p-4 border-2 border-emerald-500 rounded-xl"
                >
                    <p class="myPrimaryParagraph font-semibold mb-2">
                        Active Links
                    </p>
                    <p class="myPrimaryParagraph">
                        But what if the link have query string?
                    </p>
                    <p class="myPrimaryLink my-2 flex myPrimaryGap">
                        <Link
                            href="/test"
                            as="button"
                            :class="{
                                'font-semibold underline':
                                    route().current('test'),
                            }"
                        >
                            Test
                        </Link>
                        <Link
                            href="/dashboard"
                            as="button"
                            :class="{
                                'font-semibold underline':
                                    route().current('home'),
                            }"
                        >
                            Dashboard
                        </Link>
                    </p>
                </div>
                <!-- Test end-->

                <!-- Test start-->
                <div
                    class="mt-4 mb-8 p-4 border-2 border-emerald-500 rounded-xl"
                >
                    <p class="myPrimaryParagraph font-semibold mb-2">
                        Shared Data
                    </p>
                    <p class="myPrimaryParagraph my-2">
                        Use middleware app/HTTP/Middleware
                        "HandleInertiaRequests" for shared data accross all
                        routes.
                    </p>
                    <p class="myPrimaryParagraph my-2">
                        We do not need to declare a prop. But we can access it
                        directly via the page property which Inertia provides:
                        $page.
                    </p>
                    <p class="myPrimaryParagraph my-2">
                        Currently loggedin user email vua page component:
                        {{ $page.props.user.email }}
                    </p>
                </div>
                <!-- Test end-->
                <!-- Test start-->
                <div
                    class="mt-4 mb-8 p-4 border-2 border-emerald-500 rounded-xl"
                >
                    <p class="myPrimaryParagraph font-semibold mb-2">
                        Global Component Registration
                    </p>
                    <p class="myPrimaryParagraph my-2">Go to app.js</p>
                    <p class="myPrimaryParagraph my-2">
                        return createApp({ render: () => h(App, props) })
                        <br />
                        .use(store)
                        <br />
                        .use(plugin)
                        <br />
                        .component("link", Link)
                    </p>
                </div>
                <!-- Test end-->

                <!-- Test start-->
                <div
                    class="mt-4 mb-8 p-4 border-2 border-emerald-500 rounded-xl"
                >
                    <p class="myPrimaryParagraph font-semibold mb-2">
                        Persistent Layouts
                    </p>
                    <p class="myPrimaryParagraph my-2">
                        So, when listening to a Podcast on a Layout and then
                        switching to another layout the current layout will get
                        destroyed. This means, that the player gets reset.
                    </p>
                    <p class="myPrimaryParagraph my-2">
                        In fact, you might even notice this at Laracasts if
                        you're watching a video and then you click away, the
                        video will keep playing in the bottom-right corner. I do
                        that through persistent layouts.
                    </p>
                    <p class="myPrimaryParagraph my-2">
                        Luckily, it's really easy to implement. The solution is
                        to effectively reverse it. So rather than the layout
                        being a child of the users page, I instead want the
                        users page to be a child of the layout.
                    </p>
                    <div class="myPrimaryParagraph my-4">
                        <iframe
                            title="podcast"
                            height="200px"
                            width="100%"
                            seamless
                            src="https://player.simplecast.com/e988d91c-ce55-49bd-b0e0-f74dd973baa2?dark=false"
                        ></iframe>
                    </div>
                </div>
                <!-- Test end-->
                <!-- Test start-->
                <div
                    class="mt-4 mb-8 p-4 border-2 border-emerald-500 rounded-xl"
                >
                    <p class="myPrimaryParagraph font-semibold mb-2">
                        An Important SPA Security Concern
                    </p>
                    <p class="myPrimaryParagraph my-2">
                        If returning all users to the client, client will have
                        access to all users data like Stripe token. Only return
                        data which is relevant. Use service providers.
                    </p>
                    <p class="myPrimaryParagraph my-2">la</p>
                </div>
                <!-- Test end-->
                <!-- Test start-->
                <div
                    class="mt-4 mb-8 p-4 border-2 border-emerald-500 rounded-xl"
                >
                    <p class="myPrimaryParagraph font-semibold mb-2">
                        An Important SPA Security Concern
                    </p>
                    <p class="myPrimaryParagraph my-2">
                        If returning all users to the client, client will have
                        access to all users data like Stripe token. Only return
                        data which is relevant. Use service providers.
                    </p>
                    <p class="myPrimaryParagraph my-2">la</p>
                </div>
                <!-- Test end-->
                <!-- Test start-->
                <div
                    class="mt-4 mb-8 p-4 border-2 border-emerald-500 rounded-xl"
                >
                    <p class="myPrimaryParagraph font-semibold mb-2">
                        Fetch data using custom npm package
                    </p>

                    <div class="bg-white">
                        <div class="bg-emerald-50">
                            <h1
                                class="text-3xl font-semibold text-emerald-600 py-10 text-center"
                            >
                                Users table
                            </h1>
                        </div>
                        <div
                            class="mx-auto max-w-7xl py-24 px-6 sm:py-32 lg:px-8 lg:py-20"
                        >
                            <div
                                v-if="isErrorDataUsers && !isLoadingDataUsers"
                                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                                role="alert"
                            >
                                <span class="block sm:inline">{{
                                    isErrorDataUsers
                                }}</span>
                            </div>

                            <div
                                v-if="
                                    !isErrorDataUsers &&
                                    !isLoadingDataUsers &&
                                    isSuccessDataUsers
                                "
                                class="flex p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                                role="alert"
                            >
                                <span class="sr-only">Info</span>
                                <div>
                                    <span class="font-semibold">Success!</span>
                                    Successfully fetched users.
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div
                                    v-for="user in fetchedDataUsers"
                                    :key="user.id"
                                    class="relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-myPrimaryColor-600 focus-within:ring-offset-2 hover:border-gray-400"
                                >
                                    <div class="min-w-0 flex-1">
                                        <div class="focus:outline-none">
                                            <span
                                                class="absolute inset-0"
                                                aria-hidden="true"
                                            ></span>
                                            <p
                                                class="text-sm font-medium text-myPrimaryNormalColor"
                                            >
                                                {{ user.name }}
                                            </p>
                                            <p
                                                class="truncate text-sm text-myPrimaryNormalColor"
                                            >
                                                {{ user.email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Test end-->

                <!-- Test start-->
                <div
                    class="mt-4 mb-8 p-4 border-2 border-emerald-500 rounded-xl"
                >
                    <p class="myPrimaryParagraph font-semibold mb-2">
                        Fetch backend data using custom npm package
                    </p>
                    <p class="myPrimaryParagraph font-semibold my-12">
                        Validation Properties: {{ validationProperties }}
                    </p>

                    <div class="bg-white">
                        <div class="bg-red-50">
                            <h1
                                class="text-3xl font-semibold text-emerald-600 py-10 text-center"
                            >
                                Backend Users table
                            </h1>
                        </div>
                        <div
                            class="mx-auto max-w-7xl py-24 px-6 sm:py-32 lg:px-8 lg:py-20"
                        >
                            <div
                                v-if="
                                    isErrorBackendDataUsers &&
                                    !isLoadingBackendDataUsers
                                "
                                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                                role="alert"
                            >
                                <span class="block sm:inline">{{
                                    isErrorBackendDataUsers
                                }}</span>
                            </div>

                            <div
                                v-if="
                                    !isErrorBackendDataUsers &&
                                    !isLoadingBackendDataUsers &&
                                    isSuccessBackendDataUsers
                                "
                                class="flex p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                                role="alert"
                            >
                                <span class="sr-only">Info</span>
                                <div>
                                    <span class="font-semibold">Success!</span>
                                    Successfully fetched users.
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div
                                    v-for="user in fetchedBackendDataUsers &&
                                    fetchedBackendDataUsers.users"
                                    :key="user.id"
                                    class="relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-myPrimaryColor-500 focus-within:ring-offset-2 hover:border-gray-400"
                                >
                                    <div class="min-w-0 flex-1">
                                        <div class="focus:outline-none">
                                            <span
                                                class="absolute inset-0"
                                                aria-hidden="true"
                                            ></span>
                                            <p
                                                class="text-sm font-medium text-myPrimaryNormalColor"
                                            >
                                                {{ user.first_name }}
                                                {{ user.last_name }}
                                            </p>
                                            <p
                                                class="truncate text-sm text-myPrimaryNormalColor"
                                            >
                                                {{ user.email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end py-6 gap-4 flex-col">
                                <SubmitButton
                                    :ButtonStyleDelete="true"
                                    @firstButtonClick="getBackenUsers"
                                    :disabled="isLoadingBackendDataUsers"
                                    buttonText="Get Users"
                                >
                                </SubmitButton>
                                <SubmitButton
                                    :ButtonStyleDelete="false"
                                    @firstButtonClick="TestSubmitButtonTwice"
                                    :disabled="runMe"
                                    buttonText="Do something"
                                >
                                </SubmitButton>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Test end-->
            </div>
        </div>
    </LoggedInLayout>
</template>
