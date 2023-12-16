<script setup>
import { onMounted, ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import { TailwindPagination } from "laravel-vue-pagination";
import { useStore } from "vuex";
import { CheckIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";
import {
    ArrowRightIcon,
    ArrowUturnRightIcon,
    ArrowsRightLeftIcon,
    EllipsisVerticalIcon,
    EyeIcon,
} from "@heroicons/vue/24/outline";

// store
const store = useStore();
//
const search_query = ref("");
// handle search
const handleSearch = function (page) {
    // dispatch
    store.dispatch("mediaLibrary/loadMedia", {
        team: props.team,
        page: page,
        search_query: search_query.value,
    });
};

// props
const props = defineProps({
    team: {
        required: true,
    },
});

const getCurrentMedia = computed(() => {
    return store.getters["mediaLibrary/getCurrentMedia"];
});

const getCurrentImage = computed(() => {
    return store.getters["mediaLibrary/getCurrentImage"];
});

// get media
const getMedia = function (page) {
    // dispatch
    store.dispatch("mediaLibrary/loadMedia", {
        team: props.team,
        page: page,
        search_query: search_query.value,
    });
};
//
// get result for "laravel pagination" package
const getResultsForPage = (page = 1) => {
    search_query.value = getCurrentMedia.value?.fetchedMedia?.search_query;
    getMedia(page);
};
//
// handle image click
const handleImageClick = function (mediaLibraryId) {
    store.commit("mediaLibrary/setCurrentImage", null);
    store.commit("mediaLibrary/setCurrentPreviewImage", null);

    // dispatch
    store.dispatch("mediaLibrary/loadImage", {
        mediaLibraryId: mediaLibraryId,
        teamId: props.team.id,
    });
};
//
onMounted(() => {
    getMedia(1);
});
</script>

<template>
    <template v-if="getCurrentMedia && !getCurrentMedia.isError">
        <form class="mb-2" @submit.prevent="handleSearch(1)">
            <div class="mysearchBarWithOptions">
                <div class="relative w-full">
                    <div
                        class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"
                    >
                        <span class="material-symbols-outlined"> search </span>
                    </div>
                    <input
                        type="search"
                        id="search_query"
                        v-model="search_query"
                        class="myPrimarySearchInput"
                        autocomplete="off"
                        placeholder="Search..."
                    />
                </div>

                <button
                    @click="handleSearch(1)"
                    type="button"
                    class="myPrimarySearchButton"
                >
                    Search
                </button>
            </div>

            <template
                v-if="
                    getCurrentMedia &&
                    getCurrentMedia.fetchedMedia &&
                    getCurrentMedia.fetchedMedia.media &&
                    getCurrentMedia.fetchedMedia.media.data &&
                    !getCurrentMedia.isError
                "
            >
                <div class="flex items-center min-h-[2.5rem]">
                    <div class="flex justify-start items-center">
                        <div class="myPrimaryTag">
                            Images
                            {{ getCurrentMedia.fetchedMedia.total_results }}
                        </div>
                    </div>
                </div>
            </template>
        </form>
    </template>

    <template
        v-if="
            getCurrentMedia &&
            !getCurrentMedia.isLoading &&
            getCurrentMedia.isError
        "
    >
        <p class="myPrimaryParagraphError">
            {{ getCurrentMedia.error }}
        </p>
    </template>

    <template
        v-if="
            getCurrentMedia &&
            getCurrentMedia.fetchedMedia &&
            getCurrentMedia.fetchedMedia.media &&
            !getCurrentMedia.isError &&
            getCurrentMedia.fetchedMedia.total_results !== 0
        "
    >
        <div
            class="flex items-center justify-center border-t border-gray-200 bg-white py-3 mt-4 gap-2"
        >
            <TailwindPagination
                :limit="1"
                :keepLength="true"
                :class="[
                    'space-x-1',
                    'shadow-none',
                    'tailwind-pagination-package',
                ]"
                :active-classes="[
                    'bg-myPrimaryLinkColor',
                    'text-white',
                    'rounded-full',
                ]"
                :item-classes="[
                    'p-0',
                    'm-0',
                    'border-none',
                    'bg-myPrimaryLightGrayColor',
                    'shadow-sm',
                    'hover:bg-gray-300',
                    'text-myPrimaryDarkGrayColor',
                    'rounded-full',
                ]"
                :data="getCurrentMedia.fetchedMedia.media"
                @pagination-change-page="getResultsForPage"
            >
                <template #prev-nav>
                    <span> Prev </span>
                </template>
                <template #next-nav>
                    <span>Next</span>
                </template>
            </TailwindPagination>
        </div>
    </template>

    <!-- Loading # start -->
    <template v-if="getCurrentMedia && getCurrentMedia.isLoading">
        <SmallUniversalSpinner
            class="h-40"
            width="w-6"
            height="h-6"
            border="border-4"
        ></SmallUniversalSpinner>
    </template>
    <!-- Loading # end -->

    <template
        v-if="
            getCurrentMedia &&
            !getCurrentMedia.isError &&
            !getCurrentMedia.isLoading
        "
    >
        <div
            class="p-2 rounded pb-40 mb-40 border border-gray-200 overflow-y-scroll md:min-h-[40rem] md:max-h-[40rem] min-h-[40rem] max-h-[40rem]"
        >
            <div v-if="getCurrentMedia?.fetchedMedia?.total_results === 0">
                <div class="pt-6 py-b px-4">
                    <div
                        class="flex justify-between items-center my-2 myPrimaryTag w-max gap-4"
                    >
                        <p class="myPrimaryParagraph">
                            Sorry, no results found.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Done loading # start -->
            <div
                v-if="
                    getCurrentMedia &&
                    getCurrentMedia.fetchedMedia &&
                    getCurrentMedia.fetchedMedia.media &&
                    getCurrentMedia.fetchedMedia.media.data &&
                    !getCurrentMedia.isLoading &&
                    !getCurrentMedia.isError
                "
            >
                <div
                    class="grid lg:grid-cols-5 md:grid-cols-3 grid-cols-2 myPrimaryGap"
                >
                    <template
                        v-for="image in Array.isArray(
                            getCurrentMedia.fetchedMedia.media.data
                        ) && getCurrentMedia.fetchedMedia.media.data"
                        :key="image.id"
                    >
                        <div
                            @click="handleImageClick(image.id)"
                            class="bg-gray-50 px-0 pb-2 cursor-pointer rounded-sm"
                        >
                            <div class="flex-shrink-0">
                                <img
                                    class="group block w-full aspect-auto overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100 cursor-pointer rounded-t-sm"
                                    :src="`/storage/uploads/${image?.medium_path}`"
                                    alt="image"
                                />
                            </div>

                            <dl
                                class="myPrimaryParagraph text-xs mt-2 px-1 border-gray-200 divide-y divide-gray-200"
                            >
                                <!-- Loading # start -->
                                <dd
                                    v-if="
                                        getCurrentImage &&
                                        getCurrentImage.isLoading
                                    "
                                >
                                    <div
                                        class="pb-2 flex justify-between items-center text-xs"
                                    >
                                        <dt></dt>
                                        <div
                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                        >
                                            <SmallUniversalSpinner
                                                class="h-40"
                                                width="w-3"
                                                height="h-3"
                                                border="border-2"
                                            ></SmallUniversalSpinner>
                                        </div>
                                    </div>
                                </dd>
                                <!-- Loading # end -->
                                <dd
                                    v-if="
                                        image.id !==
                                        getCurrentImage?.currentImage
                                            ?.mediaLibrary?.id
                                    "
                                >
                                    <div
                                        class="pb-2 flex justify-between items-center text-xs"
                                    >
                                        <dt></dt>
                                        <div
                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                        >
                                            <span
                                                v-if="
                                                    getCurrentImage &&
                                                    !getCurrentImage.isLoading
                                                "
                                                class="myMediumIcon material-symbols-outlined"
                                            >
                                                more_vert
                                            </span>
                                        </div>
                                    </div>
                                </dd>

                                <dd
                                    v-if="
                                        image.id ===
                                        getCurrentImage?.currentImage
                                            ?.mediaLibrary?.id
                                    "
                                >
                                    <div
                                        class="pb-2 flex justify-between items-center text-xs"
                                    >
                                        <dt></dt>
                                        <div
                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                        >
                                            <span
                                                v-if="
                                                    getCurrentImage &&
                                                    !getCurrentImage.isLoading
                                                "
                                                class="myMediumIcon material-symbols-outlined"
                                            >
                                                check
                                            </span>
                                        </div>
                                    </div>
                                </dd>
                                <div class="py-2 flex justify-between gap-2">
                                    <dd class="text-right min-h-[2rem]">
                                        {{ image?.name ? image?.name : "—" }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </template>
                </div>
            </div>
            <!-- Done loading # end -->
        </div>
    </template>
</template>
