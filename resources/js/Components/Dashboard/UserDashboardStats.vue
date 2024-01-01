<script setup>
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import PostCardLoggedIn from "@/Components/Cards/PostCardLoggedIn.vue";
import { MinusSmallIcon, PlusSmallIcon } from "@heroicons/vue/24/outline";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import EmptySectionBorder from "@/Components/Sections/EmptySectionBorder.vue";
import { useStore } from "vuex";
import { onMounted, computed, ref } from "vue";
import { parseISO, format } from "date-fns";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import MediaLibraryModal from "@/Components/Modals/MediaLibraryModal.vue";
import ChartDefault from "@/Components/Charts/ChartDefault.vue";
import UserTag from "@/Components/Users/UserTag.vue";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";
import { router } from "@inertiajs/vue3";

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
    <div v-if="$page.props && !$page.props.currentUserTeam">
        <div>
            <h1 class="myTertiaryHeader">No Company selected</h1>
        </div>
        <p class="myPrimaryParagraph">Looks like no Company is Selected!</p>
    </div>

    <!-- Error # start -->
    <template
        v-if="
            getDashboardStats &&
            !getDashboardStats.isSuccess &&
            !getDashboardStats.isLoading &&
            getDashboardStats.isError
        "
    >
        <p class="myPrimaryParagraphError">
            {{ getDashboardStats.error }}
        </p>
    </template>
    <!-- Error # end -->

    <!-- Loading # start -->
    <template v-if="getDashboardStats && getDashboardStats.isLoading">
        <SmallUniversalSpinner
            width="w-8"
            height="h-8"
            border="border-4"
        ></SmallUniversalSpinner>
    </template>
    <!-- Loading # end -->

    <!-- Actualt data # Start -->
    <template
        v-if="$page.props && $page.props.currentUserTeam && getDashboardStats"
    >
        <div class="myPrimarySection">
            <div
                v-if="
                    getDashboardStats.fetchedData &&
                    !getDashboardStats.isLoading &&
                    !getDashboardStats.isError &&
                    getDashboardStats.isSuccess
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
                            class="myPrimaryTag inline-block hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer font-medium"
                        >
                            Latest Company Members
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
                                Looks like there are no Company Members.
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

                <!-- column images # start -->
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
                            class="myPrimaryTag inline-block hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer font-medium"
                        >
                            Latest Company Images
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
                            class="grid myPrimaryGap md:grid-cols-3 grid-cols-2"
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
                                            {{ file.width }} x
                                            {{ file.height }}
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
                <!-- column images # end -->

                <!-- column post # start -->
                <div
                    class="lg:col-span-4 w-full rounded pt-4 pb-10 px-4 bg-white h-full"
                >
                    <div class="border-b border-gray-200 mb-8 pb-2">
                        <Link
                            :href="
                                route(
                                    'team.posts.index',
                                    $page.props.user.current_team.id
                                )
                            "
                            class="myPrimaryTag inline-block hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer font-medium"
                        >
                            Latest campaigns
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
                                <PostCardLoggedIn
                                    :post="post"
                                    postListPathName="posts"
                                    postSinglePathName="post"
                                ></PostCardLoggedIn>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- column post # end -->
                <!-- column jobs # start -->
                <div
                    class="lg:col-span-4 w-full rounded pt-4 pb-10 px-4 bg-white h-full"
                >
                    <div class="border-b border-gray-200 mb-8 pb-2">
                        <Link
                            :href="
                                route(
                                    'team.jobs.index',
                                    $page.props.user.current_team.id
                                )
                            "
                            class="myPrimaryTag inline-block hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer font-medium"
                        >
                            Latest Jobs
                        </Link>
                    </div>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <template
                            v-if="
                                Array.isArray(
                                    getDashboardStats.fetchedData.latestJobs
                                ) &&
                                getDashboardStats.fetchedData.latestJobs
                                    .length === 0
                            "
                        >
                            <p class="myPrimaryParagraph">
                                Looks like there are no Jobs.
                            </p>
                        </template>
                        <ul
                            role="list"
                            class="grid myPrimaryGap md:grid-cols-2 grid-cols-2"
                        >
                            <li
                                v-for="post in getDashboardStats.fetchedData
                                    .latestJobs"
                                :key="post.id"
                                class="overflow-hidden whitespace-pre-line flex-1 bg-gray-100 h-auto rounded pb-2"
                            >
                                <!-- start photo -->

                                <PostCardLoggedIn
                                    :post="post"
                                    postListPathName="jobs"
                                    postSinglePathName="job"
                                ></PostCardLoggedIn>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- column jobs # end -->
                <!-- column stores # start -->
                <div
                    class="lg:col-span-4 w-full rounded pt-4 pb-10 px-4 bg-white h-full"
                >
                    <div class="border-b border-gray-200 mb-8 pb-2">
                        <Link
                            :href="
                                route(
                                    'team.stores.index',
                                    $page.props.user.current_team.id
                                )
                            "
                            class="myPrimaryTag inline-block hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer font-medium"
                        >
                            Latest Stores
                        </Link>
                    </div>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <template
                            v-if="
                                Array.isArray(
                                    getDashboardStats.fetchedData.latestStores
                                ) &&
                                getDashboardStats.fetchedData.latestStores
                                    .length === 0
                            "
                        >
                            <p class="myPrimaryParagraph">
                                Looks like there are no Stores.
                            </p>
                        </template>
                        <ul
                            role="list"
                            class="grid myPrimaryGap md:grid-cols-2 grid-cols-2"
                        >
                            <li
                                v-for="post in getDashboardStats.fetchedData
                                    .latestStores"
                                :key="post.id"
                                class="overflow-hidden whitespace-pre-line flex-1 bg-gray-100 h-auto rounded pb-2"
                            >
                                <!-- start photo -->

                                <PostCardLoggedIn
                                    :post="post"
                                    postListPathName="stores"
                                    postSinglePathName="store"
                                ></PostCardLoggedIn>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- column stores # end -->

                <!-- column charts # start -->
                <div
                    class="lg:col-span-12 w-full rounded pt-4 pb-10 px-4 bg-white h-full"
                >
                    <div class="border-b border-gray-200 mb-8 pb-2">
                        <div
                            class="myPrimaryTag inline-block hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer font-medium"
                        >
                            Stats
                        </div>
                    </div>

                    <ChartDefault></ChartDefault>
                </div>
                <!-- column charts # end -->
            </div>
        </div>
    </template>
</template>
