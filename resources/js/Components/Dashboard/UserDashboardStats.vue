<script setup>
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import { MinusSmallIcon, PlusSmallIcon } from "@heroicons/vue/24/outline";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import EmptySectionBorder from "@/Components/Sections/EmptySectionBorder.vue";
import { useStore } from "vuex";
import { onMounted, computed, ref } from "vue";
import { parseISO, format } from "date-fns";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import MediaLibraryModal from "@/Components/Modals/MediaLibraryModal.vue";
import UserTag from "@/Components/Users/UserTag.vue";

// store
const store = useStore();

const props = defineProps({
    user: {
        required: true,
    },
    currentUserTeam: {
        required: true,
    },
    currentUserTeamRole: {
        required: true,
    },
});

const getDashboardStats = computed(() => {
    return store.getters["userDashboard/getDashboardStats"];
});

// use media library
const showMediaLibraryModal = ref(false);
// modal content
const titleMedia = ref("");
const descriptionMedia = ref("");
const firstButtonMedia = ref("");
const secondButtonMedia = ref(null);
const thirdButtonMedia = ref(null);
// set dynamic modal handle functions
const firstMediaButtonFunction = ref(null);
const secondMediaButtonFunction = ref(null);
const thirdMediaButtonFunction = ref(null);

const handleMediaLibrary = function (imageID) {
    if (imageID && typeof imageID === "number") {
        // dispatch
        store.dispatch("mediaLibrary/loadImage", {
            mediaLibraryId: imageID,
            teamId: props.currentUserTeam.id,
        });
    }
    // handle show media library modal
    showMediaLibraryModal.value = true;

    // set media library modal standards
    titleMedia.value = "Media Library";
    descriptionMedia.value = null;
    firstButtonMedia.value = null;
    secondButtonMedia.value = null;
    thirdButtonMedia.value = null;
    // handle click
    firstMediaButtonFunction.value = function () {
        // handle show media library modal
        showMediaLibraryModal.value = false;
        store.dispatch("userDashboard/loadDashboardStats", {
            teamId: props.currentUserTeam && props.currentUserTeam.id,
        });
    };

    // end modal
};

const faqs = [
    {
        question: "Current user",
        answer: props.user,
    },
    {
        question: "Current user team",
        answer: props.currentUserTeam,
    },
    {
        question: "Current user  team role",
        answer: props.currentUserTeamRole,
    },
];

const stats = [
    { id: 1, name: "Creators on the platform", value: "8,000+" },
    { id: 2, name: "Flat platform fee", value: "3%" },
    { id: 3, name: "Uptime guarantee", value: "99.9%" },
    { id: 4, name: "Paid out to creators", value: "$70M" },
];

onMounted(() => {
    store.dispatch("userDashboard/loadDashboardStats", {
        teamId: props.currentUserTeam && props.currentUserTeam.id,
    });
});
</script>

<template>
    <FullWidthElement :descriptionArea="true">
        <template #title>Latest team resources</template>
        <template #description>
            You are currently viewing the latest resources uploaded by your
            team. These resources are sorted by date, with the most recent ones
            displayed first.
        </template>
        <template #content>
            <!-- Error - start -->
            <div
                v-if="
                    getDashboardStats &&
                    getDashboardStats.isSuccess === false &&
                    getDashboardStats.isLoading === false &&
                    getDashboardStats.isError === true
                "
                class="myPrimaryParagraphError"
            >
                {{ getDashboardStats.error }}
            </div>
            <!-- Error - end -->
            <!-- Loading - end -->
            <div
                v-if="
                    getDashboardStats &&
                    getDashboardStats.isLoading === true &&
                    getDashboardStats.isError === false
                "
                class="bordermx-auto block w-full rounded-sm object-cover object-center cursor-pointer"
            >
                <div class="flex items-center justify-center pt-12">
                    <div
                        class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                        role="status"
                    >
                        <span
                            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]"
                            >Loading...</span
                        >
                    </div>
                </div>
            </div>
            <!-- Loading - end -->
            <div v-if="$page.props && $page.props.currentUserTeam === null">
                <p class="myPrimaryParagraph">
                    Looks like no Team is Selected!
                </p>
            </div>
            <div
                v-if="
                    $page.props &&
                    $page.props.currentUserTeam !== null &&
                    getDashboardStats &&
                    getDashboardStats.fetchedData &&
                    getDashboardStats.isSuccess === true &&
                    getDashboardStats.isLoading === false &&
                    getDashboardStats.isError === false
                "
                class="grid grid-cols-1 lg:gap-8 gap-12 lg:grid-cols-12"
            >
                <!-- column start -->
                <div
                    class="lg:col-span-6 w-full rounded pt-4 pb-10 px-4 bg-white h-full"
                >
                    <div class="border-b border-gray-200 mb-8 pb-2">
                        <Link
                            :href="
                                route(
                                    'team.members',
                                    $page.props.user.current_team.id
                                )
                            "
                            class="myPrimaryTag inline-block hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer"
                        >
                            Latest Team Members
                        </Link>
                    </div>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <div
                            v-if="
                                Array.isArray(
                                    getDashboardStats.fetchedData
                                        .latestTeamMembers
                                ) &&
                                getDashboardStats.fetchedData.latestTeamMembers
                                    .length === 0
                            "
                        >
                            <p class="myPrimaryParagraph">
                                Looks like there are no Team Members.
                            </p>
                        </div>

                        <ul role="list" class="flex flex-wrap myPrimaryGap">
                            <li
                                v-for="member in getDashboardStats.fetchedData
                                    .latestTeamMembers"
                                :key="member.id"
                            >
                                <Link
                                    :href="
                                        route(
                                            'team.members',
                                            $page.props.user.current_team.id
                                        )
                                    "
                                >
                                    <UserTag
                                        customClass="my-0"
                                        :user="member"
                                        :showTeamRole="true"
                                        :clickable="true"
                                        :currentUserTeamRole="member"
                                    ></UserTag>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- column end -->

                <!-- column start -->
                <div
                    class="lg:col-span-6 w-full rounded pt-4 pb-10 px-4 bg-white h-full"
                >
                    <div class="border-b border-gray-200 mb-8 pb-2">
                        <Link
                            :href="
                                route(
                                    'media.index',
                                    $page.props.user.current_team.id
                                )
                            "
                            class="myPrimaryTag inline-block hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer"
                        >
                            Latest Team Images
                        </Link>
                    </div>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <div
                            v-if="
                                Array.isArray(
                                    getDashboardStats.fetchedData.latestMedia
                                ) &&
                                getDashboardStats.fetchedData.latestMedia
                                    .length === 0
                            "
                        >
                            <p class="myPrimaryParagraph">
                                Looks like there are no Images.
                            </p>
                        </div>
                        <ul
                            role="list"
                            class="grid myPrimaryGap md:grid-cols-2 grid-cols-2"
                        >
                            <li
                                v-for="file in getDashboardStats.fetchedData
                                    .latestMedia"
                                :key="file.id"
                                @click="handleMediaLibrary(file.id)"
                                class="rounded px-0 pb-2 cursor-pointer bg-gray-50"
                            >
                                <div
                                    class="group aspect-w-10 aspect-h-7 block w-full overflow-hidden rounded-t bg-gray-100"
                                >
                                    <img
                                        :src="`/storage/uploads/${file.medium_path}`"
                                        alt=""
                                        class="w-full pointer-events-none object-cover group-hover:opacity-75"
                                    />
                                </div>

                                <dl
                                    class="px-2 myPrimaryParagraph text-xs border-b border-myPrimaryLightGrayColor divide-y"
                                >
                                    <div
                                        class="py-3 flex justify-between items-center"
                                    >
                                        <dt class="">Dimensions</dt>
                                        <dd class="">
                                            {{ file.width }} x {{ file.height }}
                                        </dd>
                                    </div>
                                    <div
                                        class="py-3 flex justify-between items-center"
                                    >
                                        <dd class="">
                                            {{ file.name ? file.name : "–" }}
                                        </dd>
                                    </div>
                                    <div
                                        class="py-3 flex justify-between items-center"
                                    >
                                        <dt class="">Size</dt>
                                        <dd class="">{{ file.size }} KB</dd>
                                    </div>
                                </dl>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- column end -->
                <!-- column start -->
                <div
                    class="lg:col-span-6 w-full rounded pt-4 pb-10 px-4 bg-white h-full"
                >
                    <div class="border-b border-gray-200 mb-8 pb-2">
                        <Link
                            :href="
                                route(
                                    'team.posts.index',
                                    $page.props.user.current_team.id
                                )
                            "
                            class="myPrimaryTag inline-block hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer"
                        >
                            Latest Team Posts
                        </Link>
                    </div>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <template
                            v-if="
                                Array.isArray(
                                    getDashboardStats.fetchedData.latestPosts
                                ) &&
                                getDashboardStats.fetchedData.latestPosts
                                    .length === 0
                            "
                        >
                            <p class="myPrimaryParagraph">
                                Looks like there are no Posts.
                            </p>
                        </template>
                        <ul
                            role="list"
                            class="grid myPrimaryGap md:grid-cols-2 grid-cols-2"
                        >
                            <li
                                v-for="post in getDashboardStats.fetchedData
                                    .latestPosts"
                                :key="post.id"
                                class="overflow-hidden whitespace-pre-line flex-1 bg-gray-100 h-auto rounded pb-2"
                            >
                                <!-- start photo -->

                                <template
                                    v-if="post && post.cover_images !== null"
                                >
                                    <ThumbnailSmallImageSlider
                                        :images="post.cover_images"
                                        imageSize="medium_path"
                                        imageHeight="max-h-96"
                                        imageWidth="w-full object-cover"
                                        :roundedFull="false"
                                        :squareButtons="true"
                                    ></ThumbnailSmallImageSlider>
                                </template>

                                <div class="px-2 pt-4 pb-2">
                                    <Link
                                        :href="
                                            route('team.posts.post.show', [
                                                $page.props.user.current_team
                                                    .id,
                                                post.id,
                                                post.slug,
                                            ])
                                        "
                                    >
                                        <p
                                            class="text-sm font-medium mt-2 mb-2"
                                        >
                                            {{ post.title.slice(0, 40)
                                            }}{{
                                                post.title.length > 40
                                                    ? ".."
                                                    : ""
                                            }}
                                        </p>
                                    </Link>
                                    <p
                                        v-html="
                                            `${post.content.slice(0, 100)}..`
                                        "
                                        class="myPrimaryParagraph text-xs"
                                    ></p>

                                    <Link
                                        class="mt-4 inline-block"
                                        :href="
                                            route('team.posts.post.show', [
                                                $page.props.user.current_team
                                                    .id,
                                                post.id,
                                                post.slug,
                                            ])
                                        "
                                    >
                                        <span class="myPrimaryButton text-xs">
                                            Read more
                                        </span>
                                    </Link>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- column end -->
                <!-- column start -->
                <div
                    class="lg:col-span-6 w-full rounded pt-4 pb-10 px-4 bg-white h-full"
                >
                    <div class="border-b border-gray-200 mb-8 pb-2">
                        <div
                            class="myPrimaryTag inline-block hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer"
                        >
                            Latest Team Stats
                        </div>
                    </div>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <div class="mx-auto">
                            <h3 class="myFourthHeader">
                                Trusted by thousands of creators&nbsp;worldwide
                            </h3>
                            <p class="myPrimaryParagrph">
                                Lorem ipsum, dolor sit amet consectetur
                                adipisicing elit. Maiores impedit perferendis
                                suscipit eaque, iste dolor cupiditate blanditiis
                                ratione.
                            </p>
                            <dl
                                class="mt-16 grid max-w-xl grid-cols-1 gap-8 sm:mt-20 sm:grid-cols-2 xl:mt-16"
                            >
                                <div
                                    v-for="stat in stats"
                                    :key="stat.id"
                                    class="flex flex-col gap-y-3 border-l border-gray-900/10 pl-6"
                                >
                                    <dt class="myPrimaryParagraph">
                                        {{ stat.name }}
                                    </dt>
                                    <dd
                                        class="order-first text-3xl font-semibold tracking-tight text-gray-900"
                                    >
                                        {{ stat.value }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
                <!-- column end -->
            </div>
        </template>
    </FullWidthElement>

    <FullWidthElement :descriptionArea="true">
        <template #title
            >Details about the logged-in user and team insights</template
        >
        <template #description>
            Retrieve details about the logged-in user and team insights for
            current user. Discover insights about the logged-in user and the
            team.
        </template>
        <template #content>
            <div class="grid grid-cols-1 md:grid-cols-12 lg:gap-12 gap-18">
                <div class="col-span-1 md:col-span-8">
                    <h2 class="my-2 mb-4 myPrimaryParagraph font-medium">
                        Details about the logged-in user
                    </h2>
                    <dl class="divide-y divide-myPrimaryMediumGrayColor">
                        <Disclosure
                            as="div"
                            v-for="faq in faqs"
                            :key="faq.question"
                            v-slot="{ open }"
                        >
                            <dt>
                                <DisclosureButton
                                    class="py-6 flex w-full items-center justify-between text-left myPrimaryParagraph"
                                >
                                    <span class="myPrimaryParagraph"
                                        >{{ faq.question }}
                                    </span>
                                    <span class="ml-6 flex h-7 items-center">
                                        <PlusSmallIcon
                                            v-if="!open"
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                        <MinusSmallIcon
                                            v-else
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                    </span>
                                </DisclosureButton>
                            </dt>
                            <DisclosurePanel as="dd" class="mt-2 pr-12 pb-16">
                                <p
                                    class="myPrimaryParagraph whitespace-pre-line"
                                >
                                    {{ faq.answer }}
                                </p>
                            </DisclosurePanel>
                        </Disclosure>
                    </dl>
                </div>

                <div class="col-span-1 md:col-span-4">
                    <h2 class="my-2 mb-4 myPrimaryParagraph font-medium">
                        Somthing here
                    </h2>
                    <div>
                        <p class="myPrimaryParagraph">Details here</p>
                    </div>
                </div>
            </div>
            <MediaLibraryModal
                :user="user"
                :team="currentUserTeam"
                :open="showMediaLibraryModal"
                :title="titleMedia"
                :description="descriptionMedia"
                :firstButtonText="firstButtonMedia"
                :secondButtonText="secondButtonMedia"
                :thirdButtonText="thirdButtonMedia"
                @firstMediaButtonFunction="firstMediaButtonFunction"
                @secondMediaButtonFunction="secondMediaButtonFunction"
                @thirdMediaButtonFunction="thirdMediaButtonFunction"
            >
            </MediaLibraryModal>
        </template>
    </FullWidthElement>
</template>
