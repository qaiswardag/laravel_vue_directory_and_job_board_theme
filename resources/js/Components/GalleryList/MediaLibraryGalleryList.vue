<script setup>
import { onMounted, ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import { TailwindPagination } from "laravel-vue-pagination";
import { useStore } from "vuex";
import { CheckIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";

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
    <form class="mb-2" @submit.prevent="handleSearch(1)">
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
                            stroke-width="1.5"
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
                getCurrentMedia.isError === false &&
                getCurrentMedia.fetchedMedia.total_results !== 0
            "
            class="flex items-center min-h-[2.5rem]"
        >
            <div class="flex justify-start items-center">
                <p class="myPrimaryTag">
                    Images
                    {{ getCurrentMedia.fetchedMedia.total_results }}
                </p>
            </div>
        </div>
    </form>
    <div
        v-if="
            getCurrentMedia &&
            getCurrentMedia.isLoading === false &&
            getCurrentMedia.isError === true
        "
        class="myPrimaryParagraphError"
    >
        {{ getCurrentMedia.error }}
    </div>

    <div
        v-if="
            getCurrentMedia &&
            getCurrentMedia.fetchedMedia &&
            getCurrentMedia.fetchedMedia.media &&
            getCurrentMedia.fetchedMedia.total_results !== 0
        "
        class="flex items-center justify-center border-t border-gray-200 bg-white py-3 mt-4 gap-2"
    >
        <TailwindPagination
            :limit="1"
            :keepLength="true"
            :class="['space-x-1', 'shadow-none', 'tailwind-pagination-package']"
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

    <div
        class="border overflow-y-scroll md:min-h-[33rem] md:max-h-[33rem] min-h-[15rem] max-h-[15rem] p-2 rounded"
    >
        <div v-if="getCurrentMedia?.fetchedMedia?.total_results === 0">
            <p class="myPrimaryParagraph">
                It looks like there are no images..
            </p>
        </div>

        <!-- Not loading # start -->
        <div
            v-if="
                getCurrentMedia &&
                getCurrentMedia.isLoading &&
                !getCurrentMedia.isError
            "
        >
            <SmallUniversalSpinner
                class="h-40"
                width="w-6"
                height="h-6"
                border="border-4"
            ></SmallUniversalSpinner>
        </div>
        <!-- Not loading # end -->

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
                class="grid lg:grid-cols-6 md:grid-cols-4 grid-cols-2 myPrimaryGap"
            >
                <transition-group name="fade">
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
                            <img
                                class="group aspect-w-10 aspect-h-7 block w-full overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100 cursor-pointer rounded-t-sm"
                                :class="{
                                    '':
                                        image.id ===
                                        getCurrentImage?.currentImage
                                            ?.mediaLibrary?.id,
                                }"
                                :src="`/storage/uploads/${image?.medium_path}`"
                                alt="image"
                            />

                            <dl
                                class="myPrimaryParagraph text-xs mt-2 px-1 border-gray-200 divide-y divide-gray-200"
                            >
                                <dd
                                    v-if="
                                        image.id !==
                                        getCurrentImage?.currentImage
                                            ?.mediaLibrary?.id
                                    "
                                >
                                    <div
                                        class="py-3 flex justify-between items-center"
                                    >
                                        <dt></dt>
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
                                        class="py-3 flex justify-between items-center"
                                    >
                                        <dt class="">Selected</dt>
                                        <CheckIcon
                                            class="w-4 h-4 text-myPrimaryLinkColor"
                                        >
                                        </CheckIcon>
                                    </div>
                                </dd>
                                <div class="py-2 flex justify-between gap-2">
                                    <dd class="text-right">
                                        {{ image?.name ? image?.name : "—" }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </template>
                </transition-group>
            </div>
        </div>
        <!-- Done loading # end -->
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
