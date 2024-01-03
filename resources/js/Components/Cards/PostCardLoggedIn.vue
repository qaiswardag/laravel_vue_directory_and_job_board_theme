<script setup>
import { router } from "@inertiajs/vue3";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";

const props = defineProps({
    post: {
        required: true,
    },
    postListPathName: {
        required: true,
    },
    postSinglePathName: {
        required: true,
    },
});

const goToSinglePost = function (currentTeamId, postId, postSlug) {
    router.get(
        route(
            `team.${props.postListPathName}.${props.postSinglePathName}.show`,
            [currentTeamId, postSlug, postId]
        )
    );
};
</script>
<template>
    <template v-if="post && post.cover_images !== null">
        <ThumbnailSmallImageSlider
            :images="post.cover_images"
            imageSize="medium_path"
            imageHeight="max-h-96"
            imageWidth="w-full object-cover"
            :roundedFull="false"
            :squareButtons="true"
            @firstButtonClick="
                goToSinglePost(
                    $page.props.user.current_team.id,
                    post.id,
                    post.slug
                )
            "
            :imageClickable="true"
        ></ThumbnailSmallImageSlider>
    </template>

    <div class="px-2 pt-4 pb-2">
        <p
            @click="
                goToSinglePost(
                    $page.props.user.current_team.id,
                    post.id,
                    post.slug
                )
            "
            class="text-xs mt-2 mb-2 text-myPrimaryLinkColor font-medium cursor-pointer"
        >
            {{ post.title.slice(0, 35)
            }}{{ post.title.length > 35 ? ".." : "" }}
        </p>
    </div>
</template>
