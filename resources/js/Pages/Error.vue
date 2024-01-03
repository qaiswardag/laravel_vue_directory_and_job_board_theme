<script setup>
import { Link } from "@inertiajs/vue3";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    status: {
        Number,
        required: true,
    },
    customError: {
        Number,
        required: false,
    },
});

const title = computed(() => {
    return {
        503: "503: Service Unavailable",
        500: "500: Server Error",
        422: "422: Unprocessable Content",
        405: "405: Method Not Allowed",
        404: "404: Page Not Found.",
        403: "403: Forbidden",
        401: "401: Unauthorized",
    }[props.status];
});

const description = computed(() => {
    return {
        503: `Whoops, Server error response code indicates that the server is not ready to handle the request.`,
        500: "Whoops, something went wrong on our servers.",
        422: "Sorry, something went wrong with your request.",
        405: "Sorry, this method is not allowed",
        404: "Sorry, the page or content you are looking for could not be found.",
        403: "Sorry, you are forbidden from accessing this page or perform this action.",
        401: "Sorry, request has not been completed because it lacks valid authentication credentials for the requested resource.",
    }[props.status];
});

const handleBack = function () {
    window.history.back();
};
</script>

<template>
    <Head title="Error" />

    <div class="relative isolate min-h-screen">
        <img
            src="/brand-images/pink-lady-high-resolution.jpg"
            alt=""
            class="absolute inset-0 -z-10 h-full w-full object-cover object-top"
        />
        <div class="bg-black/20 absolute top-0 left-0 w-full h-full"></div>

        <div
            class="lg:max-w-3xl flex flex-col gap-2 justify-center items-center mx-auto"
        >
            <div
                class="mx-auto max-w-7xl px-6 py-32 text-center sm:py-40 lg:px-8 z-20"
            >
                <div>
                    <p class="text-base font-normal leading-8 text-white">
                        {{ props.status }}
                    </p>
                    <h1
                        class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-5xl"
                    >
                        {{ title }}
                    </h1>
                    <p
                        class="myPrimaryParagraph text-lg text-white drop-shadow font-medium mt-8 leading-6"
                    >
                        {{ description }}
                    </p>
                    <p
                        class="myPrimaryParagraph text-lg text-white drop-shadow font-medium leading-6"
                    >
                        {{ customError }}
                    </p>
                    <div class="mt-10 flex justify-center">
                        <button
                            @click="handleBack"
                            class="myPrimaryLink text-white"
                        >
                            <span aria-hidden="true">&larr;</span> Back
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
