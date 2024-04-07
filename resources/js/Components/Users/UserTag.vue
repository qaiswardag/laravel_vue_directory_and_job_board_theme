<script setup>
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    user: {
        required: true,
    },
    showTeamRole: {
        required: false,
    },
    currentUserTeamRole: {
        required: false,
    },
    clickable: {
        required: false,
    },
    customClass: {
        default: null,
        required: false,
    },
    showJobTitle: {
        default: null,
        required: false,
    },
});

const goToUser = function () {
    if (
        usePage() &&
        usePage().props &&
        usePage().props.environment === "local"
    ) {
        window.open(
            `http://localhost:3000/me/${props.user.username}`,
            "_blank"
        );
    }
    if (
        usePage() &&
        usePage().props &&
        usePage().props.environment !== "local"
    ) {
        window.open(
            `https://www.myself.ae/me/${props.user.username}`,
            "_blank"
        );
    }
};
</script>

<template>
    <div
        v-if="user"
        @click="goToUser"
        :class="[
            {
                'hover:bg-opacity-60 cursor-pointer': clickable,
            },

            customClass,
        ]"
        class="myPrimaryTag inline-block py-0 px-0"
    >
        <div class="flex items-center rounded-full">
            <div class="rounded-l-full">
                <div
                    v-if="
                        user.user_photo &&
                        Array.isArray(user.user_photo) &&
                        user.user_photo.length !== 0
                    "
                    class="px-2 rounded-l-full min-h-[3rem] flex items-center justify-center"
                >
                    <ThumbnailSmallImageSlider
                        :images="user.user_photo"
                        imageSize="thumbnail_path"
                        imageHeight="h-8 min-h-8 max-h-8"
                        imageWidth="w-8 min-w-8 max-w-8 object-cover"
                        :roundedFull="true"
                    ></ThumbnailSmallImageSlider>
                </div>

                <div
                    v-if="
                        user.user_photo &&
                        Array.isArray(user.user_photo) &&
                        user.user_photo.length === 0
                    "
                    class="pl-2"
                >
                    <div
                        class="text-white flex-shrink-0 h-8 w-8 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs rounded-l-full"
                    >
                        {{ user.first_name.charAt(0).toUpperCase() }}
                        {{ user.last_name.charAt(0).toUpperCase() }}
                    </div>
                </div>
            </div>

            <button type="button">
                <div
                    class="flex flex-col items-start justify-center min-h-[3rem] px-2 text-myPrimaryDarkGrayColor"
                    :class="[
                        user.user_photo ? 'rounded-r-full' : 'rounded-full',
                    ]"
                >
                    <span class="h-full text-xs">
                        {{ user.first_name }}
                        {{ user.last_name }}
                    </span>
                    <template v-if="showJobTitle && user.job_title">
                        <span class="text-xs font-normal">
                            {{ `title: ${user.job_title}` }}
                        </span>
                    </template>

                    <template v-if="showTeamRole && currentUserTeamRole">
                        <span class="text-xs font-normal">
                            role:
                            {{
                                currentUserTeamRole?.key
                                    ? currentUserTeamRole.key
                                    : null
                            }}
                            {{
                                currentUserTeamRole?.role
                                    ? currentUserTeamRole.role
                                    : null
                            }}
                        </span>
                    </template>
                </div>
            </button>
            <template v-if="user.public === 1">
                <div class="ml-4 mr-2">
                    <span class="flex h-2 w-2 rounded-full bg-green-800"></span>
                </div>
            </template>
            <template v-if="user.public === 0">
                <div class="ml-4 mr-2">
                    <span class="flex h-2 w-2 rounded-full bg-red-600"></span>
                </div>
            </template>
        </div>
    </div>
    <!-- user photo - end -->
    <div v-if="!user" class="myPrimaryTag inline-block py-2">Unknown</div>
</template>
