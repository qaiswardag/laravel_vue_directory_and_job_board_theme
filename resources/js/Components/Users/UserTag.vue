<script setup>
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";

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
</script>

<template>
    <div
        v-if="user"
        :class="[
            {
                'hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer':
                    clickable,
            },

            customClass,
        ]"
        class="myPrimaryTag inline-block"
    >
        <div class="flex items-center gap-2">
            <div
                v-if="
                    user.user_photo &&
                    Array.isArray(user.user_photo) &&
                    user.user_photo.length !== 0
                "
            >
                <ThumbnailSmallImageSlider
                    :images="user.user_photo"
                    imageSize="thumbnail_path"
                    imageHeight="h-10"
                    imageWidth="w-10"
                    :roundedFull="true"
                ></ThumbnailSmallImageSlider>
            </div>

            <Link
                :href="route('users.guest.show', user.username)"
                class="text-myPrimaryDarkGrayColor hover:text-white"
            >
                <div
                    v-if="
                        user.user_photo &&
                        Array.isArray(user.user_photo) &&
                        user.user_photo.length === 0
                    "
                    class="text-white flex-shrink-0 h-10 w-10 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs"
                >
                    {{ user.first_name.charAt(0).toUpperCase() }}
                    {{ user.last_name.charAt(0).toUpperCase() }}
                </div>

                <div class="flex flex-col items-start justify-start">
                    <span class="font-medium">
                        {{ user.first_name }}
                        {{ user.last_name }}
                    </span>
                    <template v-if="showJobTitle && user.job_title">
                        <p class="text-xs font-normal">
                            {{ `title: ${user.job_title}` }}
                        </p>
                    </template>

                    <template v-if="showTeamRole && currentUserTeamRole">
                        <p class="text-xs font-normal">
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
                        </p>
                    </template>
                </div>
            </Link>
        </div>
    </div>
    <!-- user photo - end -->
    <div v-if="!user" class="myPrimaryTag inline-block py-2">Unknown</div>
</template>
