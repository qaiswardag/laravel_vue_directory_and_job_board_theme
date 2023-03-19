<script setup>
import { onMounted, ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import { vueFetch } from "use-lightweight-fetch";
import { TailwindPagination } from "laravel-vue-pagination";
import { useStore } from "vuex";

// store
const store = useStore();
//
const search_query = ref("");
// handle search
const handleSearch = function (page) {
    // dispatch
    store.dispatch("mediaLibrary/loadMedia", {
        teamId: props.team.id,
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
        teamId: props.team.id,
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
    <div
        v-if="getCurrentMedia && getCurrentMedia.isError"
        class="myPrimaryParagraphError"
    >
        {{ getCurrentMedia.isError }}
    </div>

    <form class="mb-4" @submit.prevent="handleSearch(1)">
        <div class="mysearchBarWithOptions">
            <div class="relative w-full">
                <div
                    class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"
                >
                    <svg
                        aria-hidden="true"
                        class="w-5 h-5 text-gray-500 dark:text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        ></path>
                    </svg>
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
                class="myPrimaryButton"
            >
                Search
            </button>
        </div>

        <div class="flex items-center min-h-[2.5rem] bg-red-300">
            <div
                v-if="
                    getCurrentMedia &&
                    getCurrentMedia.fetchedMedia &&
                    getCurrentMedia.fetchedMedia.media &&
                    getCurrentMedia.fetchedMedia.media.data &&
                    getCurrentMedia.isLoading === false &&
                    (getCurrentMedia.isError === null ||
                        getCurrentMedia.isError === false)
                "
            >
                <div
                    class="flex justify-start items-center"
                    v-if="getCurrentMedia.fetchedMedia.total_results !== 0"
                >
                    <p
                        class="myPrimaryParagraph text-myPrimaryDarkGrayColor bg-myPrimaryLightGrayColor rounded my-1 px-2 py-2 text-xs"
                    >
                        Results
                        {{ getCurrentMedia.fetchedMedia.total_results }}
                    </p>
                </div>
            </div>
        </div>
    </form>

    <div
        v-if="
            getCurrentMedia &&
            getCurrentMedia.fetchedMedia &&
            getCurrentMedia.fetchedMedia.media &&
            getCurrentMedia.fetchedMedia.total_results !== 0
        "
        class="scale-90 text-center bottom-0 w-full my-2 border-b border-gray-200 py-2"
    >
        <TailwindPagination
            :active-classes="['bg-black', 'text-white', 'border-black']"
            :limit="2"
            :class="['flex', 'justify-center', 'items-center', 'rounded-full']"
            :data="getCurrentMedia.fetchedMedia.media"
            @pagination-change-page="getResultsForPage"
        >
        </TailwindPagination>
    </div>

    <div
        class="overflow-y-scroll mb-4 p-4 border border-myPrimaryMediumGrayColor rounded"
    >
        <div
            v-if="
                getCurrentMedia &&
                getCurrentMedia.isLoading === true &&
                (getCurrentMedia.isError === null ||
                    getCurrentMedia.isError === false)
            "
        >
            <div
                class="flex items-center justify-center max-h-[30rem] min-h-[25rem]"
            >
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
        <div
            v-if="
                getCurrentMedia &&
                getCurrentMedia.fetchedMedia &&
                getCurrentMedia.fetchedMedia.media &&
                getCurrentMedia.fetchedMedia.media.data &&
                getCurrentMedia.isLoading === false &&
                (getCurrentMedia.isError === null ||
                    getCurrentMedia.isError === false)
            "
        >
            <div
                v-if="getCurrentMedia.fetchedMedia.total_results === 0"
                class="min-h-[25rem]"
            >
                <p class="myPrimaryParagraph">No images.</p>
            </div>

            <div
                class="grid lg:grid-cols-4 md:grid-cols-4 grid-cols-2 myPrimaryGap max-h-[30rem] min-h-[25rem]"
            >
                <template
                    v-for="image in getCurrentMedia &&
                    getCurrentMedia.fetchedMedia &&
                    getCurrentMedia.fetchedMedia.media.data"
                    :key="image.id"
                >
                    <div
                        @click="handleImageClick(image.id)"
                        class="border border-myPrimaryLightGrayColor rounded px-2 p-2 cursor-pointer"
                    >
                        <img
                            class="group aspect-w-10 aspect-h-7 h-32 block w-full overflow-hidden rounded bg-gray-100 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100 cursor-pointer"
                            :class="{
                                '':
                                    image.id ===
                                    getCurrentImage?.currentImage?.mediaLibrary
                                        ?.id,
                            }"
                            :src="`/uploads/${image.path}`"
                            alt="image"
                        />

                        <p
                            class="myPrimaryParagraph text-xs italic pl-1 break-words"
                        ></p>

                        <dl
                            class="myPrimaryParagraph text-xs mt-2 border-gray-200 divide-y divide-gray-200"
                        >
                            <div class="py-3 flex justify-between items-center">
                                <dt class="">Selected</dt>
                                <dd
                                    v-if="
                                        image.id ===
                                        getCurrentImage?.currentImage
                                            ?.mediaLibrary?.id
                                    "
                                    class=""
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        class="w-4 h-4 text-green-600"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M4.5 12.75l6 6 9-13.5"
                                        />
                                    </svg>
                                </dd>
                            </div>
                            <div class="py-2 flex justify-between gap-2">
                                <dt class="text-left">Width:</dt>
                                <dd class="text-right">
                                    {{ image.width }}
                                </dd>
                            </div>
                            <div class="py-2 flex justify-between gap-2">
                                <dt class="text-left">Height:</dt>
                                <dd class="text-right">
                                    {{ image.height }}
                                </dd>
                            </div>
                            <div class="py-2 flex justify-between gap-2">
                                <dt class="text-left">Size:</dt>
                                <dd class="text-right">{{ image.size }} KB</dd>
                            </div>
                            <div class="py-2 flex justify-between gap-2">
                                <dt class="text-left">Name:</dt>
                                <dd class="text-right">
                                    {{ image.name ? image.name : "–" }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
