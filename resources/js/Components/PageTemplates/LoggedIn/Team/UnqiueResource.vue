<script setup>
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import DefaultTemplate from "@/Components/PageTemplates/DefaultTemplate.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import { parseISO, format } from "date-fns";
import ArticleTemplate from "@/Components/PageTemplates/ArticleTemplate.vue";
import WidgetSectionBorder from "@/Components/Sections/WidgetSectionBorder.vue";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import UserTag from "@/Components/Users/UserTag.vue";

import {
    BriefcaseIcon,
    CheckIcon,
    GlobeAmericasIcon,
    MapIcon,
    MapPinIcon,
    NewspaperIcon,
    PencilIcon,
    Squares2X2Icon,
    SquaresPlusIcon,
    TagIcon,
    TrashIcon,
    UserIcon,
} from "@heroicons/vue/24/outline";

defineProps({
    post: {
        required: true,
    },
    authors: {
        required: false,
    },
    countries: {
        required: false,
    },
    states: {
        required: false,
    },
    jobTypes: {
        required: false,
    },
    categories: {
        required: false,
    },
    coverImages: {
        required: false,
    },
});
</script>
<template>
    <div class="myPrimaryMainPageHeaderParagraph my-0 py-0"></div>
    <ArticleTemplate :sidebarArea="true" :actionsArea="false">
        <template #main>
            <h1 class="myPrimaryMainPageHeaderNotLoggedIn">
                {{ post.title }}
            </h1>
            <section v-html="post.content"></section>
        </template>
        <template #sidebar>
            <aside class="flex gap-8 flex-col">
                <!-- updated by - end -->
                <!-- Post updated by - start -->
                <div class="myPrimaryWidget">
                    <h4 class="myFourthHeader">Updated By</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <UserTag :user="post.updatedBy"></UserTag>
                </div>
                <!-- updated by - end -->

                <!-- published # start -->
                <div v-if="post.published" class="myPrimaryWidget">
                    <h4 class="myFourthHeader">Published</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <span class="myPrimaryTag bg-myPrimaryLinkColor text-white"
                        >Published</span
                    >
                </div>
                <div v-if="!post.published" class="myPrimaryWidget">
                    <h4 class="myFourthHeader">Published</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <span class="myPrimaryTag bg-myPrimaryErrorColor text-white"
                        >Unpublished</span
                    >
                </div>
                <!-- published # end -->

                <!-- countries # start -->
                <div
                    v-if="
                        countries &&
                        Array.isArray(countries) &&
                        countries.length !== 0
                    "
                    class="myPrimaryWidget"
                >
                    <h4 class="myFourthHeader">Country</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <div
                        class="flex flex-wrap justify-start items-center gap-2"
                    >
                        <p
                            v-for="jobCountry in countries && countries"
                            :key="jobCountry"
                            class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                        >
                            <GlobeAmericasIcon
                                class="w-3 h-3 stroke-1"
                            ></GlobeAmericasIcon>
                            <span>
                                {{ jobCountry.name }}
                            </span>
                        </p>
                    </div>
                </div>
                <!-- countries # end -->
                <!-- states # start -->
                <div
                    v-if="
                        states && Array.isArray(states) && states.length !== 0
                    "
                    class="myPrimaryWidget"
                >
                    <h4 class="myFourthHeader">Location</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <div
                        class="flex flex-wrap justify-start items-center gap-2"
                    >
                        <p
                            v-if="post.address"
                            class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                        >
                            <MapPinIcon class="w-3 h-3 stroke-1"></MapPinIcon>
                            <span>
                                {{ post.address }}
                            </span>
                        </p>
                        <p
                            v-for="state in states"
                            :key="state"
                            class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                        >
                            <MapPinIcon class="w-3 h-3 stroke-1"></MapPinIcon>
                            <span>
                                {{ state.name }}
                            </span>
                        </p>
                    </div>
                </div>
                <!-- states # end -->
                <!-- job types # start -->
                <div
                    v-if="
                        jobTypes &&
                        Array.isArray(jobTypes) &&
                        jobTypes.length !== 0
                    "
                    class="myPrimaryWidget"
                >
                    <h4 class="myFourthHeader">Job type</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <div
                        class="flex flex-wrap justify-start items-center gap-2"
                    >
                        <p
                            v-for="state in jobTypes && jobTypes"
                            :key="state"
                            class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                        >
                            <NewspaperIcon
                                class="w-3 h-3 stroke-1"
                            ></NewspaperIcon>
                            <span>
                                {{ state.name }}
                            </span>
                        </p>
                    </div>
                </div>
                <!-- job types # end -->
                <!-- categories # start -->
                <div
                    v-if="
                        categories &&
                        Array.isArray(categories) &&
                        categories.length !== 0
                    "
                    class="myPrimaryWidget"
                >
                    <h4 class="myFourthHeader">Categories</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <div
                        class="flex flex-wrap justify-start items-center gap-2"
                    >
                        <p
                            v-for="state in categories && categories"
                            :key="state"
                            class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                        >
                            <Squares2X2Icon
                                class="w-3 h-3 stroke-1"
                            ></Squares2X2Icon>
                            <span>
                                {{ state.name }}
                            </span>
                        </p>
                    </div>
                </div>
                <!-- categories # end -->
                <!-- tags # start -->
                <div
                    v-if="
                        categories &&
                        Array.isArray(categories) &&
                        categories.length !== 0
                    "
                    class="myPrimaryWidget"
                >
                    <h4 class="myFourthHeader">Tags</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <div
                        class="flex flex-wrap justify-start items-center gap-2"
                    >
                        <div
                            class="flex flex-wrap justify-start items-center gap-2"
                        >
                            <p
                                v-for="tag in post.tags &&
                                post.tags
                                    .split(',')
                                    .sort((a, b) => a.localeCompare(b))"
                                :key="tag"
                                class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                            >
                                <TagIcon class="w-3 h-3 stroke-1"></TagIcon>
                                <span>
                                    {{ tag }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- tags # end -->

                <!-- show authors # start -->
                <div v-if="post.show_author" class="myPrimaryWidget">
                    <h4 class="myFourthHeader">People visibility</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <span class="myPrimaryTag bg-myPrimaryLinkColor text-white"
                        >Visible</span
                    >
                </div>
                <div v-if="!post.show_author" class="myPrimaryWidget">
                    <h4 class="myFourthHeader">People visibility</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <span class="myPrimaryTag bg-myPrimaryErrorColor text-white"
                        >Hidden</span
                    >
                </div>
                <!-- show authors # end -->

                <!-- authors # start -->
                <div
                    v-if="
                        post.show_author &&
                        Array.isArray(authors) &&
                        authors.length !== 0
                    "
                    class="myPrimaryWidget"
                >
                    <h4 class="myFourthHeader">People</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <div
                        class="flex flex-wrap justify-start items-center gap-1"
                    >
                        <div
                            v-for="author in authors"
                            :key="author"
                            class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                        >
                            <div v-if="author.profile_photo_path !== null">
                                <div class="h-5 w-5 flex-shrink-0">
                                    <img
                                        class="object-cover h-5 w-5 rounded-full"
                                        :src="`/storage/${author.profile_photo_path}`"
                                        alt="avatar"
                                    />
                                </div>
                            </div>

                            <div
                                style="font-size: 9px; gap: 2px"
                                v-if="author.profile_photo_path === null"
                                class="flex-shrink-0 h-5 w-5 rounded-full bg-myPrimaryBrandColor flex justify-center items-center font-normal text-white text-xs"
                            >
                                <span>
                                    {{
                                        author.first_name
                                            .charAt(0)
                                            .toUpperCase()
                                    }}
                                </span>
                                <span>
                                    {{
                                        author.last_name.charAt(0).toUpperCase()
                                    }}
                                </span>
                            </div>
                            <span
                                class="flex flex-col items-left gap-1 myPrimaryParagraph"
                            >
                                <p
                                    style="font-size: 10px"
                                    class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1 pl-0 pr-1 flex justify-center items-center gap-1"
                                >
                                    <span>
                                        {{ author.first_name }}
                                        {{ author.last_name }}
                                    </span>
                                </p>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- authors # end -->
                <!-- cover images - start -->
                <ThumbnailSmallImageSlider
                    v-if="post.cover_images"
                    :images="post.cover_images"
                    imageSize="large_path"
                    imageHeight="h-auto"
                    imageWidth="w-full"
                    :roundedFull="false"
                    :squareButtons="true"
                ></ThumbnailSmallImageSlider>
                <!-- cover images - end -->
            </aside>
        </template>
    </ArticleTemplate>
</template>
