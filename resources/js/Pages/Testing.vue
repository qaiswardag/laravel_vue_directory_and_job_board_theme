<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import { Link } from "@inertiajs/vue3";

defineProps({
    currentTime: {
        required: true,
    },
});
</script>

<template>
    <LoggedInLayout title="Testing">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Testing page
            </h2>
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
                            :data="{ foo: 'bar' }"
                            :headers="{
                                _token: this.$page.props.csrf_token,
                            }"
                        >
                            Log me out
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
                    <p class="myPrimaryParagraph">
                        Current time is: {{ currentTime }}
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
                            href="/logout"
                            as="button"
                            :class="{
                                'font-semibold underline':
                                    route().current('test'),
                            }"
                        >
                            Testing
                        </Link>
                        <Link
                            href="/logout"
                            as="button"
                            :class="{
                                'font-semibold underline':
                                    route().current('home'),
                            }"
                        >
                            Settings
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
                    <p class="myPrimaryParagraph">La la la la la</p>
                </div>
                <!-- Test end-->
            </div>
        </div>
    </LoggedInLayout>
</template>
