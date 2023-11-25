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
    CalendarDaysIcon,
    CheckIcon,
    GlobeAmericasIcon,
    MapIcon,
    MapPinIcon,
    NewspaperIcon,
    PencilIcon,
    Square3Stack3DIcon,
    Squares2X2Icon,
    SquaresPlusIcon,
    TagIcon,
    TrashIcon,
    UserIcon,
} from "@heroicons/vue/24/outline";

defineProps({
    onlyForCurrentTeam: {
        required: true,
    },
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
    <div class="myPrimaryHeaderParagraph my-0 py-0"></div>
    <ArticleTemplate :sidebarArea="true" :actionsArea="false">
        <template #main>
            <h1 class="myPrimaryHeader text-center">
                {{ post.title }}
            </h1>
            <section v-html="post.content"></section>
        </template>
        <template #sidebar>
            <aside class="flex gap-8 flex-col">
                <!-- Post updated by - start -->
                <template v-if="onlyForCurrentTeam">
                    <div class="myPrimaryWidget">
                        <h4 class="myQuaternaryHeader">Updated By</h4>
                        <WidgetSectionBorder></WidgetSectionBorder>
                        <UserTag :user="post.updatedBy"></UserTag>
                    </div>
                </template>
                <!-- Post updated by - end -->

                <!-- published # start -->
                <template v-if="onlyForCurrentTeam">
                    <div v-if="post.published" class="myPrimaryWidget">
                        <h4 class="myQuaternaryHeader">Published</h4>
                        <WidgetSectionBorder></WidgetSectionBorder>
                        <div
                            class="myPrimaryTag bg-myPrimaryLinkColor text-white"
                        >
                            Published
                        </div>
                    </div>
                    <div v-if="!post.published" class="myPrimaryWidget">
                        <h4 class="myQuaternaryHeader">Published</h4>
                        <WidgetSectionBorder></WidgetSectionBorder>
                        <div
                            class="myPrimaryTag bg-myPrimaryErrorColor text-white"
                        >
                            Unpublished
                        </div>
                    </div>
                </template>
                <!-- published # end -->
                <!-- is filled # start -->
                <template v-if="post.is_filled">
                    <div class="myPrimaryWidget">
                        <h4 class="myQuaternaryHeader">Application status</h4>
                        <WidgetSectionBorder></WidgetSectionBorder>
                        <div
                            class="myPrimaryTag bg-myPrimaryErrorColor text-white"
                        >
                            Closed for new applications
                        </div>
                    </div>
                </template>
                <!-- is filled # end -->

                <!-- apply # start -->
                <template v-if="post.apply_via_link || post.apply_via_email">
                    <div class="myPrimaryWidget">
                        <h4 class="myQuaternaryHeader">Apply</h4>
                        <WidgetSectionBorder></WidgetSectionBorder>

                        <div
                            class="flex myPrimaryGap flex-col justify-normal items-start"
                        >
                            <template v-if="post.apply_via_link">
                                <a :href="post.apply_via_link" target="_blank">
                                    <button
                                        type="button"
                                        class="myPrimaryButton"
                                    >
                                        Apply
                                    </button>
                                </a>
                            </template>
                            <template v-if="post.apply_via_email">
                                <button type="button" class="myPrimaryButton">
                                    Copy email
                                </button>
                                <p
                                    v-if="post.apply_via_email"
                                    class="text-[10px]"
                                >
                                    Email: {{ post.apply_via_email }}
                                </p>
                            </template>
                        </div>
                    </div>
                </template>
                <!-- apply # end -->

                <!-- started_at # start -->
                <template v-if="post.started_at && post.ended_at">
                    <div class="myPrimaryWidget">
                        <h4 class="myQuaternaryHeader">
                            Job publish & end date
                        </h4>
                        <WidgetSectionBorder></WidgetSectionBorder>
                        <div class="flex flex-col gap-2">
                            <template v-if="post.started_at">
                                <div class="flex items-center gap-4">
                                    <span
                                        class="myMediumIcon material-symbols-outlined"
                                    >
                                        calendar_month
                                    </span>
                                    <p class="myPrimaryParagraph">
                                        {{
                                            format(
                                                parseISO(post.started_at),
                                                "dd. MMMM yyyy"
                                            )
                                        }}
                                    </p>
                                </div>
                            </template>
                            <template v-if="post.ended_at">
                                <div class="flex items-center gap-4">
                                    <span
                                        class="myMediumIcon material-symbols-outlined"
                                    >
                                        calendar_month
                                    </span>
                                    <p class="myPrimaryParagraph">
                                        {{
                                            format(
                                                parseISO(post.ended_at),
                                                "dd. MMMM yyyy"
                                            )
                                        }}
                                    </p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
                <!-- started_at # end -->

                <!-- countries # start -->
                <div
                    v-if="
                        countries &&
                        Array.isArray(countries) &&
                        countries.length !== 0
                    "
                    class="myPrimaryWidget"
                >
                    <h4 class="myQuaternaryHeader">Country</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <div
                        class="flex flex-wrap justify-start items-center gap-2"
                    >
                        <p
                            v-for="jobCountry in countries && countries"
                            :key="jobCountry"
                            class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                        >
                            <span
                                class="myMediumIcon material-symbols-outlined"
                            >
                                globe
                            </span>
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
                    <h4 class="myQuaternaryHeader">Location</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <div
                        class="flex flex-wrap justify-start items-center gap-2"
                    >
                        <p
                            v-for="state in states"
                            :key="state"
                            class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                        >
                            <span
                                class="myMediumIcon material-symbols-outlined"
                            >
                                location_on
                            </span>
                            <span>
                                {{ state.name }}
                            </span>
                        </p>
                        <p
                            v-if="post.address"
                            class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                        >
                            <span
                                class="myMediumIcon material-symbols-outlined"
                            >
                                location_on
                            </span>
                            <span>
                                {{ post.address }}
                            </span>
                        </p>
                        <p
                            v-if="post.floor"
                            class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                        >
                            <span
                                class="myMediumIcon material-symbols-outlined"
                            >
                                category
                            </span>
                            <span>
                                {{
                                    post.floor === 0 || post.floor === "0"
                                        ? "Ground floor"
                                        : `Floor ${post.floor}`
                                }}
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
                    <h4 class="myQuaternaryHeader">Job type</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <div
                        class="flex flex-wrap justify-start items-center gap-2"
                    >
                        <p
                            v-for="state in jobTypes && jobTypes"
                            :key="state"
                            class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                        >
                            <span
                                class="myMediumIcon material-symbols-outlined"
                            >
                                book_4
                            </span>
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
                    <h4 class="myQuaternaryHeader">Categories</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <div
                        class="flex flex-wrap justify-start items-center gap-2"
                    >
                        <p
                            v-for="state in categories && categories"
                            :key="state"
                            class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                        >
                            <span
                                class="myMediumIcon material-symbols-outlined"
                            >
                                category
                            </span>
                            <span>
                                {{ state.name }}
                            </span>
                        </p>
                    </div>
                </div>
                <!-- categories # end -->
                <!-- tags # start -->
                <template v-if="post.tags">
                    <div class="myPrimaryWidget">
                        <h4 class="myQuaternaryHeader">Tags</h4>
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
                                    class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                                >
                                    <span
                                        class="myMediumIcon material-symbols-outlined"
                                    >
                                        loyalty
                                    </span>
                                    <span>
                                        {{ tag }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- tags # end -->

                <!-- Url for contact page # start -->
                <template v-if="post.contact_page_url">
                    <div class="myPrimaryWidget">
                        <h4 class="myQuaternaryHeader">Contact Store</h4>
                        <WidgetSectionBorder></WidgetSectionBorder>
                        <div
                            class="flex flex-wrap justify-start items-center gap-2"
                        >
                            <a :href="post.contact_page_url" target="_blank">
                                <button type="button" class="myPrimaryButton">
                                    Contact store
                                </button>
                            </a>
                        </div>
                    </div>
                </template>
                <!-- Url for contact page # end -->

                <!-- show authors # start -->
                <template v-if="onlyForCurrentTeam">
                    <div v-if="post.show_author" class="myPrimaryWidget">
                        <h4 class="myQuaternaryHeader">People visibility</h4>
                        <WidgetSectionBorder></WidgetSectionBorder>
                        <div
                            class="myPrimaryTag bg-myPrimaryLinkColor text-white"
                        >
                            Visible
                        </div>
                    </div>
                    <div v-if="!post.show_author" class="myPrimaryWidget">
                        <h4 class="myQuaternaryHeader">People visibility</h4>
                        <WidgetSectionBorder></WidgetSectionBorder>
                        <div
                            class="myPrimaryTag bg-myPrimaryErrorColor text-white"
                        >
                            Hidden
                        </div>
                    </div>
                    <!-- show authors # end -->
                </template>

                <!-- authors # start -->
                <div
                    v-if="
                        post.show_author &&
                        Array.isArray(authors) &&
                        authors.length !== 0
                    "
                    class="myPrimaryWidget"
                >
                    <h4 class="myQuaternaryHeader">People</h4>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <div
                        class="flex flex-wrap justify-start items-center gap-1"
                    >
                        <div
                            v-for="author in authors"
                            :key="author"
                            class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
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
                                class="flex-shrink-0 h-5 w-5 rounded-full bg-myPrimaryBrandColor flex justify-center items-center font-normal text-white text-sm"
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
                                    class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1 pl-0 pr-1 flex justify-center items-center gap-1"
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
                    imageWidth="w-full rounded-md"
                    :roundedFull="false"
                    :squareButtons="true"
                ></ThumbnailSmallImageSlider>
                <!-- cover images - end -->
            </aside>
        </template>
    </ArticleTemplate>
</template>
