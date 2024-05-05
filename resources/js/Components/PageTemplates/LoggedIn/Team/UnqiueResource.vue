<script setup>
import { usePage } from "@inertiajs/vue3";
import { parseISO, format } from "date-fns";
import ArticleTemplate from "@/Components/PageTemplates/ArticleTemplate.vue";
import WidgetSectionBorder from "@/Components/Sections/WidgetSectionBorder.vue";
import UserTag from "@/Components/Users/UserTag.vue";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
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
import { onMounted, ref } from "vue";

defineProps({
    onlyForCurrentTeam: {
        required: true,
    },
    post: {
        required: true,
    },
    team: {
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

    postType: {
        required: true,
    },
});
</script>
<template>
    <div class="my-0 py-0"></div>
    <ArticleTemplate :sidebarArea="true" :actionsArea="false">
        <template #main>
            <h1 class="myPrimaryHeader text-center">
                {{ post.title }}
            </h1>
            <div id="page-builder-editor">
                <section v-html="post.content"></section>
            </div>
        </template>
        <template #sidebar>
            <aside class="flex gap-8 flex-col">
                <!-- team related to this resource # start -->
                <template v-if="team">
                    <div class="myPrimaryWidget">
                        <h4 class="myQuaternaryHeader flex items-center gap-2">
                            <div>
                                {{ team?.name }}
                            </div>
                            <!-- Url for contact page # start -->
                            <template v-if="post.contact_page_url">
                                <div>
                                    <a
                                        :href="post.contact_page_url"
                                        target="_blank"
                                        :title="`Website for ${team?.name}`"
                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                    >
                                        <span
                                            class="myMediumIcon material-symbols-outlined"
                                        >
                                            link
                                        </span>
                                    </a>
                                </div>
                            </template>
                            <!-- Url for contact page # end -->
                        </h4>

                        <WidgetSectionBorder></WidgetSectionBorder>

                        <template v-if="team.coverImagesWithLogos?.logos">
                            <ThumbnailSmallImageSlider
                                :images="team.coverImagesWithLogos?.logos"
                                imageSize="large_path"
                                imageHeight="min-h-24 max-h-24"
                                imageWidth="w-24 min-w-24 max-w-24 mb-4 w-24 object-cover"
                                :roundedFull="true"
                                :squareButtons="true"
                            ></ThumbnailSmallImageSlider>
                        </template>
                        <template
                            v-if="team.coverImagesWithLogos?.cover_images"
                        >
                            <ThumbnailSmallImageSlider
                                v-if="team.coverImagesWithLogos?.cover_images"
                                :images="
                                    team.coverImagesWithLogos?.cover_images
                                "
                                imageSize="large_path"
                                imageHeight="max-h-96"
                                imageWidth="w-full object-cover rounded-b-none"
                                :roundedFull="false"
                                :squareButtons="true"
                            ></ThumbnailSmallImageSlider>
                        </template>
                    </div>
                </template>
                <!-- team related to this resource # start -->

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
                        <h4 class="myQuaternaryHeader">Start & end date</h4>
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
                            class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-2"
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

                <!-- opening hours # start -->
                <template v-if="postType === 'Store'">
                    <div class="myPrimaryWidget">
                        <h4 class="myQuaternaryHeader">Opening hours</h4>
                        <WidgetSectionBorder></WidgetSectionBorder>

                        <!-- Store opening hours # start -->
                        <template v-if="post.use_store_opening_hours">
                            <div
                                class="flex flex-col myPrimaryGap bg-myPrimaryLightGrayColor rounded-lg px-2 py-4"
                            >
                                <!--  monday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Monday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            post.monday_opening_time &&
                                            post.monday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ post.monday_opening_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            post.monday_opening_time &&
                                            post.monday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ post.monday_closing_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.monday_opening_time ||
                                            !post.monday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.monday_opening_time ||
                                            !post.monday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- monday weekday # end -->

                                <div class="border border-gray-200"></div>

                                <!--  tuesday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Tuesday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            post.tuesday_opening_time &&
                                            post.tuesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ post.tuesday_opening_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            post.tuesday_opening_time &&
                                            post.tuesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ post.tuesday_closing_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.tuesday_opening_time ||
                                            !post.tuesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.tuesday_opening_time ||
                                            !post.tuesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- tuesday weekday # end -->

                                <div class="border border-gray-200"></div>

                                <!--  wednesday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Wednesday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            post.wednesday_opening_time &&
                                            post.wednesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{
                                                    post.wednesday_opening_time
                                                }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            post.wednesday_opening_time &&
                                            post.wednesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{
                                                    post.wednesday_closing_time
                                                }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.wednesday_opening_time ||
                                            !post.wednesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.wednesday_opening_time ||
                                            !post.wednesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- wednesday weekday # end -->

                                <div class="border border-gray-200"></div>

                                <!--  thursday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Thursday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            post.thursday_opening_time &&
                                            post.thursday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ post.thursday_opening_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            post.thursday_opening_time &&
                                            post.thursday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ post.thursday_closing_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.thursday_opening_time ||
                                            !post.thursday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.thursday_opening_time ||
                                            !post.thursday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- thursday weekday # end -->

                                <div class="border border-gray-200"></div>

                                <!--  friday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Friday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            post.friday_opening_time &&
                                            post.friday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ post.friday_opening_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            post.friday_opening_time &&
                                            post.friday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ post.friday_closing_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.friday_opening_time ||
                                            !post.friday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.friday_opening_time ||
                                            !post.friday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- friday weekday # end -->

                                <div class="border border-gray-200"></div>

                                <!--  saturday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Saturday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            post.saturday_opening_time &&
                                            post.saturday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ post.saturday_opening_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            post.saturday_opening_time &&
                                            post.saturday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ post.saturday_closing_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.saturday_opening_time ||
                                            !post.saturday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.saturday_opening_time ||
                                            !post.saturday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- saturday weekday # end -->

                                <div class="border border-gray-200"></div>

                                <!--  sunday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Sunday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            post.sunday_opening_time &&
                                            post.sunday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ post.sunday_opening_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            post.sunday_opening_time &&
                                            post.sunday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ post.sunday_closing_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.sunday_opening_time ||
                                            !post.sunday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !post.sunday_opening_time ||
                                            !post.sunday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- sunday weekday # end -->
                            </div>
                        </template>
                        <!-- Store opening hours # end -->

                        <!-- Team opening hours # start -->
                        <template v-if="post.use_team_opening_hours">
                            <div
                                class="flex flex-col myPrimaryGap bg-myPrimaryLightGrayColor rounded-lg px-2 py-4"
                            >
                                <!--  monday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Monday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            team.monday_opening_time &&
                                            team.monday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ team.monday_opening_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            team.monday_opening_time &&
                                            team.monday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ team.monday_closing_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.monday_opening_time ||
                                            !team.monday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.monday_opening_time ||
                                            !team.monday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- monday weekday # end -->

                                <div class="border border-gray-200"></div>

                                <!--  tuesday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Tuesday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            team.tuesday_opening_time &&
                                            team.tuesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ team.tuesday_opening_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            team.tuesday_opening_time &&
                                            team.tuesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ team.tuesday_closing_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.tuesday_opening_time ||
                                            !team.tuesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.tuesday_opening_time ||
                                            !team.tuesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- tuesday weekday # end -->

                                <div class="border border-gray-200"></div>

                                <!--  wednesday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Wednesday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            team.wednesday_opening_time &&
                                            team.wednesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{
                                                    team.wednesday_opening_time
                                                }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            team.wednesday_opening_time &&
                                            team.wednesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{
                                                    team.wednesday_closing_time
                                                }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.wednesday_opening_time ||
                                            !team.wednesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.wednesday_opening_time ||
                                            !team.wednesday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- wednesday weekday # end -->

                                <div class="border border-gray-200"></div>

                                <!--  thursday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Thursday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            team.thursday_opening_time &&
                                            team.thursday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ team.thursday_opening_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            team.thursday_opening_time &&
                                            team.thursday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ team.thursday_closing_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.thursday_opening_time ||
                                            !team.thursday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.thursday_opening_time ||
                                            !team.thursday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- thursday weekday # end -->

                                <div class="border border-gray-200"></div>

                                <!--  friday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Friday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            team.friday_opening_time &&
                                            team.friday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ team.friday_opening_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            team.friday_opening_time &&
                                            team.friday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ team.friday_closing_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.friday_opening_time ||
                                            !team.friday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.friday_opening_time ||
                                            !team.friday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- friday weekday # end -->

                                <div class="border border-gray-200"></div>

                                <!--  saturday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Saturday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            team.saturday_opening_time &&
                                            team.saturday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ team.saturday_opening_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            team.saturday_opening_time &&
                                            team.saturday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ team.saturday_closing_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.saturday_opening_time ||
                                            !team.saturday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.saturday_opening_time ||
                                            !team.saturday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- saturday weekday # end -->

                                <div class="border border-gray-200"></div>

                                <!--  sunday # start -->
                                <div class="flex items-center">
                                    <div class="w-1/3 py-2 px-2 rounded-md">
                                        <p
                                            class="myPrimaryParagraph tracking-tight"
                                        >
                                            Sunday:
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            team.sunday_opening_time &&
                                            team.sunday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ team.sunday_opening_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <template
                                        v-if="
                                            team.sunday_opening_time &&
                                            team.sunday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                {{ team.sunday_closing_time }}
                                            </p>
                                        </div>
                                    </template>

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.sunday_opening_time ||
                                            !team.sunday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->

                                    <!-- store is closed # start -->
                                    <template
                                        v-if="
                                            !team.sunday_opening_time ||
                                            !team.sunday_closing_time
                                        "
                                    >
                                        <div class="w-1/3 py-2 px-2 rounded-md">
                                            <p
                                                class="myPrimaryParagraph tracking-tight"
                                            >
                                                Closed
                                            </p>
                                        </div>
                                    </template>
                                    <!-- store is closed # end -->
                                </div>
                                <!-- sunday weekday # end -->
                            </div>
                        </template>
                        <!-- Team opening hours # end -->
                    </div>
                </template>
                <!-- opening hours # end -->

                <!-- location # start -->
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
                        <div
                            v-for="state in states"
                            :key="state"
                            class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2"
                        >
                            <div class="flex items-center gap-2">
                                <div>
                                    <span
                                        class="myMediumIcon material-symbols-outlined"
                                    >
                                        location_on
                                    </span>
                                </div>
                                <div>
                                    <span>
                                        {{ state.name
                                        }}{{ post.address ? ", " : "" }}
                                    </span>

                                    <span v-if="post.address">
                                        <span> {{ post.address }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <p
                            v-if="post.floor"
                            class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-2"
                        >
                            <span
                                class="myMediumIcon material-symbols-outlined"
                            >
                                floor
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
                <!-- location # end -->

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
                            class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-2"
                        >
                            <span
                                class="myMediumIcon material-symbols-outlined"
                            >
                                sell
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
                            v-for="category in categories && categories"
                            :key="category.id"
                            class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-2"
                        >
                            <span
                                class="myMediumIcon material-symbols-outlined"
                            >
                                interests
                            </span>
                            <span>
                                {{ category.name }}
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
                                    class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-2"
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

                <!-- show authors # start -->
                <template v-if="onlyForCurrentTeam && postType !== 'Post'">
                    <div v-if="post.show_author" class="myPrimaryWidget">
                        <template v-if="postType === 'Job'">
                            <h4 class="myQuaternaryHeader">
                                Show Job Recruiters
                            </h4>
                        </template>
                        <template v-if="postType === 'Store'">
                            <h4 class="myQuaternaryHeader">
                                Show Store Staff Members
                            </h4>
                        </template>
                        <WidgetSectionBorder></WidgetSectionBorder>
                        <div
                            class="myPrimaryTag bg-myPrimaryLinkColor text-white"
                        >
                            Visible
                        </div>
                    </div>
                    <div v-if="!post.show_author" class="myPrimaryWidget">
                        <template v-if="postType === 'Job'">
                            <h4 class="myQuaternaryHeader">
                                Show Job Recruiters
                            </h4>
                        </template>
                        <template v-if="postType === 'Store'">
                            <h4 class="myQuaternaryHeader">
                                Show Store Staff Members
                            </h4>
                        </template>
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
                    <template v-if="postType === 'Job'">
                        <h4 class="myQuaternaryHeader">Job Recruiters</h4>
                    </template>
                    <template v-if="postType === 'Store'">
                        <h4 class="myQuaternaryHeader">Store Staff Members</h4>
                    </template>
                    <WidgetSectionBorder></WidgetSectionBorder>
                    <div
                        class="flex flex-wrap justify-start items-center gap-1"
                    >
                        <div
                            v-for="author in authors"
                            :key="author"
                            class="text-sm rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-2"
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
                <!-- cover images for resource # start -->
                <div class="myPrimaryWidget">
                    <h4 class="myQuaternaryHeader flex items-center gap-2">
                        {{ post.title }}
                        <!-- brand website # start -->
                        <template v-if="post.brand_website_url">
                            <div class="flex items-center gap-4">
                                <a
                                    :href="post.brand_website_url"
                                    target="_blank"
                                    :title="`Website for ${post?.title}`"
                                    class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                >
                                    <div
                                        class="myMediumIcon material-symbols-outlined"
                                    >
                                        link
                                    </div>
                                </a>
                            </div>
                        </template>
                        <!-- brand website # end -->
                    </h4>

                    <WidgetSectionBorder></WidgetSectionBorder>
                    <!-- post logo # start -->
                    <ThumbnailSmallImageSlider
                        v-if="post.brand_logos"
                        :images="post.brand_logos"
                        imageSize="medium_path"
                        imageHeight="min-h-24 max-h-24 rounded-full"
                        imageWidth="w-24 min-w-24 max-w-24 mb-4 w-24 object-cover"
                        :roundedFull="false"
                        :squareButtons="true"
                    ></ThumbnailSmallImageSlider>
                    <!-- post logo # end -->

                    <template v-if="post.cover_images">
                        <!-- start photo -->
                        <template
                            v-if="
                                post &&
                                post.cover_images !== null &&
                                postType === 'Post'
                            "
                        >
                            <ThumbnailSmallImageSlider
                                :images="post.cover_images"
                                imageSize="large_path"
                                imageHeight="xl:max-h-[20rem] xl:min-h-[20rem] xl:h-[20rem] lg:max-h-[22rem] min-h-[22rem] lg:h-[22rem] md:max-h-[18rem] min-h-[18rem] md:h-[18rem] max-h-[26rem] min-h-[26rem] h-[26rem]"
                                imageWidth="w-full object-cover rounded-b-none object-top"
                                :roundedFull="false"
                                :squareButtons="true"
                                :imageClickable="false"
                            ></ThumbnailSmallImageSlider>
                        </template>
                        <template
                            v-if="
                                post &&
                                post.cover_images !== null &&
                                postType === 'Job'
                            "
                        >
                            <ThumbnailSmallImageSlider
                                :images="post.cover_images"
                                imageSize="large_path"
                                imageHeight="xl:max-h-[20rem] xl:min-h-[20rem] xl:h-[20rem] lg:max-h-[22rem] min-h-[22rem] lg:h-[22rem] md:max-h-[18rem] min-h-[18rem] md:h-[18rem] max-h-[26rem] min-h-[26rem] h-[26rem]"
                                imageWidth="w-full object-cover rounded-b-none object-top"
                                :roundedFull="false"
                                :squareButtons="true"
                                :imageClickable="false"
                            ></ThumbnailSmallImageSlider>
                        </template>
                        <template
                            v-if="
                                post &&
                                post.cover_images !== null &&
                                postType === 'Store'
                            "
                        >
                            <ThumbnailSmallImageSlider
                                :images="post.cover_images"
                                imageSize="large_path"
                                imageHeight="xl:max-h-[34rem] xl:min-h-[34rem] xl:h-[34rem] lg:max-h-[34rem] min-h-[34rem] lg:h-[34rem] md:max-h-[z-30rem] min-h-[z-30rem] md:h-[z-30rem] max-h-[34rem] min-h-[34rem] h-[34rem]"
                                imageWidth="w-full object-cover rounded-b-none object-top"
                                :roundedFull="false"
                                :squareButtons="true"
                                :imageClickable="false"
                            ></ThumbnailSmallImageSlider>
                        </template>
                    </template>
                </div>
                <!-- cover images for resource # end -->
            </aside>
        </template>
    </ArticleTemplate>
</template>
