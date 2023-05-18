<script setup>
import { Link } from "@inertiajs/vue3";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { Head } from "@inertiajs/vue3";

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
        405: "405: Method Not Allowed",
        404: "404: Page Not Found.",
        403: "403: Forbidden",
    }[props.status];
});

const description = computed(() => {
    return {
        503: "Sorry, we are doing some maintenance. Please check back soon.",
        500: "Whoops, something went wrong on our servers.",
        405: "Sorry, this method is not allowed",
        404: "Sorry, the page or content you are looking for could not be found.",
        403: "Sorry, you are forbidden from accessing this page or perform this action.",
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
            src="https://images.unsplash.com/photo-1545972154-9bb223aac798?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=3050&q=80&exp=8&con=-15&sat=-75"
            alt=""
            class="absolute inset-0 -z-10 h-full w-full object-cover object-top"
        />
        <div class="mx-auto max-w-7xl px-6 py-32 text-center sm:py-40 lg:px-8">
            <p class="text-base font-semibold leading-8 text-white">
                {{ props.status }}
            </p>
            <h1
                class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-5xl"
            >
                {{ title }}
            </h1>
            <p class="mt-4 text-base text-white/70 sm:mt-6">
                {{ description }}
            </p>
            <p class="mt-2 text-base text-white/70 sm:mt-2">
                {{ customError }}
            </p>
            <div class="mt-10 flex justify-center">
                <button @click="handleBack" class="myPrimaryLink text-white">
                    <span aria-hidden="true">&larr;</span> Back
                </button>
            </div>
        </div>
    </div>
</template>
