<script setup>
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { vueFetch } from "use-lightweight-fetch";
import { TailwindPagination } from "laravel-vue-pagination";
import { useStore } from "vuex";

// store
const store = useStore();

// use fetch package
const {
    handleData,
    fetchedData: media,
    isError,
    validationProperties,
    isLoading,
    isSuccess,
} = vueFetch();

// handle search
const handleSearch = function () {
    console.log("search me");
};

// props
const props = defineProps({
    media: {
        // required: true,
    },
    team: {
        required: true,
    },
});

const images = ref([]);

// get media
const getMedia = function (page) {
    handleData(`/overview/media/index/${props.team.id}/?page=${page}`);
};
//
// get result for "laravel pagination" package
const getResultsForPage = (page = 1) => {
    getMedia(page);
};
//
// handle image click
const handleImageClick = function (mediaLibraryId) {
    // dispatch
    store.dispatch("mediaLibrary/getImage", {
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
                class="myPrimarySearchInput"
                autocomplete="off"
                placeholder="placeholderInput"
            />
        </div>

        <button @click="handleSearch" type="button" class="myPrimaryButton">
            Search
        </button>
    </div>

    <p v-if="isError && !isSuccess" class="myPrimaryParagraphError">
        {{ isError }}
    </p>

    <div class="overflow-y-scroll max-h-[40rem] min-h-[30rem] mt-2 mb-4 p-4">
        <div
            v-if="isLoading === true && (isError === null || isError === false)"
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
                isLoading === false &&
                media &&
                (isError === null || isError === false)
            "
        >
            <div
                class="grid lg:grid-cols-4 md:grid-cols-4 grid-cols-2 myPrimaryGap"
            >
                <template
                    v-for="image in media && media.data && media.data"
                    :key="image.id"
                >
                    <img
                        @click="handleImageClick(image.id)"
                        class="mx-auto block w-24 h-full rounded-lg object-cover object-center cursor-pointer hover:shadow-sm hover:scale-105 transition-all"
                        :src="`/${image.path}`"
                        alt="image"
                    />
                </template>
            </div>
        </div>
    </div>

    <div
        v-if="
            isLoading === false &&
            media &&
            (isError === null || isError === false)
        "
        class="text-center mt-4"
    >
        <TailwindPagination
            :active-classes="['bg-black', 'text-white', 'border-black']"
            :limit="2"
            class="mt-4 flex justify-center items-center"
            :data="media"
            @pagination-change-page="getResultsForPage"
        >
        </TailwindPagination>
    </div>
</template>
