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

const goToSinglePost = function (current_teamId, postId, postSlug) {
    router.get(
        route(
            `team.${props.postListPathName}.${props.postSinglePathName}.show`,
            [current_teamId, postSlug, postId]
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
        <Link
            :href="
                route('team.posts.post.show', [
                    $page.props.user.current_team.id,
                    post.slug,
                    post.id,
                ])
            "
        >
            <p class="text-sm font-medium mt-2 mb-2">
                {{ post.title.slice(0, 25)
                }}{{ post.title.length > 25 ? ".." : "" }}
            </p>
        </Link>
    </div>
</template>
