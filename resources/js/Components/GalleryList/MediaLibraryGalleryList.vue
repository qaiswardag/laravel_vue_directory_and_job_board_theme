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
                    class="myPrimaryParagraph text-myPrimaryDarkGrayColor bg-myPrimaryLightGrayColor rounded my-2 px-2 py-1 text-xs"
                >
                    Results
                    {{ getCurrentMedia.fetchedMedia.total_results }}
                </p>
            </div>
        </div>
    </form>

    <div class="overflow-y-scroll max-h-[30rem] min-h-[15rem] mt-2 mb-4 p-4">
        <div
            v-if="
                getCurrentMedia &&
                getCurrentMedia.isLoading === true &&
                (getCurrentMedia.isError === null ||
                    getCurrentMedia.isError === false)
            "
        >
            <div class="flex items-center justify-center min-h-80 max-h-80">
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
                class=""
                v-if="getCurrentMedia.fetchedMedia.total_results === 0"
            >
                <p class="myPrimaryParagraph">Media Library is empty.</p>
            </div>

            <div
                class="grid lg:grid-cols-6 md:grid-cols-4 grid-cols-2 myPrimaryGap"
            >
                <template
                    v-for="image in getCurrentMedia &&
                    getCurrentMedia.fetchedMedia &&
                    getCurrentMedia.fetchedMedia.media.data"
                    :key="image.id"
                >
                    <div>
                        <img
                            @click="handleImageClick(image.id)"
                            class="mx-auto block h-28 w-full rounded-sm object-cover object-center cursor-pointer hover:shadow-sm hover:scale-105 transition-all"
                            :class="{
                                'border-spacing-8 border-4 border-myPrimaryBrandColor':
                                    image.id ===
                                    getCurrentImage?.currentImage?.id,
                            }"
                            :src="`/${image.path}`"
                            alt="image"
                        />
                        <p
                            class="myPrimaryParagraph text-xs italic pl-1 break-words"
                        >
                            {{ image.name ? image.name : "–" }}
                        </p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div
        v-if="
            getCurrentMedia &&
            getCurrentMedia.fetchedMedia &&
            getCurrentMedia.fetchedMedia.media
        "
        class="text-center mt-4"
    >
        <TailwindPagination
            :active-classes="['bg-black', 'text-white', 'border-black']"
            :limit="2"
            class="mt-4 flex justify-center items-center"
            :data="getCurrentMedia.fetchedMedia.media"
            @pagination-change-page="getResultsForPage"
        >
        </TailwindPagination>
    </div>
</template>
