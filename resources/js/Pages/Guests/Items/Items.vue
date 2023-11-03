<script setup>
import { useStore } from "vuex";
import { vueFetch } from "use-lightweight-fetch";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import { onMounted, ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import slugify from "slugify";
import config from "@/utils/config";
import { TailwindPagination } from "laravel-vue-pagination";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import ItemsFilterSelection from "@/Pages/Guests/Items/ItemsFilterSelection/ItemsFilterSelection.vue";
import ItemDisplaySelection from "@/Pages/Guests/Items/ItemsFilterSelection/ItemDisplaySelection.vue";

import {
    GlobeAmericasIcon,
    MapIcon,
    MapPinIcon,
    NewspaperIcon,
    Square2StackIcon,
    Squares2X2Icon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    nameList: {
        required: true,
    },
    pathList: {
        required: true,
    },
    oldInput: {
        search_query: {
            required: false,
        },
    },
});

const store = useStore();

// form
const searchForm = useForm({
    search_query: "",
});

// get images
const {
    handleData: handleGetPosts,
    fetchedData: fetchedDataPosts,
    isError: isErrorPosts,
    error: errorPosts,
    errors: errorsPosts,
    isLoading: isLoadingPosts,
    isSuccess: isSuccessPosts,
} = vueFetch();

const categorySelected = ref([]);
const typeSelected = ref([]);
const countrySelected = ref([]);
const stateSelected = ref([]);

const handleSearch = function () {
    handleGetPosts(
        route(props.pathList, {
            search_query: searchForm.search_query,
            type: typeSelected.value,
            category: categorySelected.value,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};

const fetchComponents = function (page) {
    //remember old search value while paginating
    if (fetchedDataPosts.value?.oldInput?.search_query) {
        searchForm.search_query =
            fetchedDataPosts.value?.oldInput?.search_query;
    }

    handleGetPosts(
        route(props.pathList, {
            page: page,
            search_query: searchForm.search_query,
            type: typeSelected.value,
            category: categorySelected.value,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};

// Scroll to the top of the page
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
}

// get result for "laravel pagination" package
const getResultsForPage = (page = 1) => {
    fetchComponents(page);
    scrollToTop();
};

// handle type # start
const handleRemoveType = function (selectedItem) {
    if (!selectedItem) {
        return;
    }

    // Find the index of the selected item in categorySelected.value
    const index = typeSelected.value.findIndex(
        (item) => item.id === selectedItem.id
    );

    // If the item is found, remove it from the array
    if (index !== -1) {
        typeSelected.value.splice(index, 1);
    }

    handleGetPosts(
        route(props.pathList, {
            page: 1,
            search_query: searchForm.search_query,
            type: typeSelected.value,
            category: categorySelected.value,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};
const handleSelectType = function (selectedItem) {
    if (!selectedItem) {
        return;
    }
    // Check if there is no object with the same id
    if (typeSelected.value.some((item) => item.id === selectedItem.id)) {
        return;
    }

    typeSelected.value.push(selectedItem);

    handleGetPosts(
        route(props.pathList, {
            page: 1,
            search_query: searchForm.search_query,
            type: typeSelected.value,
            category: categorySelected.value,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};
// handle type # end

// handle category # start
const handleRemoveCategory = function (selectedItem) {
    if (!selectedItem) {
        return;
    }

    // Find the index of the selected item in categorySelected.value
    const index = categorySelected.value.findIndex(
        (item) => item.id === selectedItem.id
    );

    // If the item is found, remove it from the array
    if (index !== -1) {
        categorySelected.value.splice(index, 1);
    }

    handleGetPosts(
        route(props.pathList, {
            page: 1,
            search_query: searchForm.search_query,
            type: typeSelected.value,
            category: categorySelected.value,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};
const handleSelectCategory = function (selectedItem) {
    if (!selectedItem) {
        return;
    }
    // Check if there is no object with the same id
    if (categorySelected.value.some((item) => item.id === selectedItem.id)) {
        return;
    }

    categorySelected.value.push(selectedItem);

    handleGetPosts(
        route(props.pathList, {
            page: 1,
            search_query: searchForm.search_query,
            type: typeSelected.value,
            category: categorySelected.value,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};
// handle category # end

// handle country # start
const handleRemoveCountry = function (selectedItem) {
    if (!selectedItem) {
        return;
    }

    // Find the index of the selected item in categorySelected.value
    const index = countrySelected.value.findIndex(
        (item) => item.id === selectedItem.id
    );

    // If the item is found, remove it from the array
    if (index !== -1) {
        countrySelected.value.splice(index, 1);
    }

    handleGetPosts(
        route(props.pathList, {
            page: 1,
            search_query: searchForm.search_query,
            type: typeSelected.value,
            category: categorySelected.value,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};
const handleSelectCountry = function (selectedItem) {
    if (!selectedItem) {
        return;
    }
    // Check if there is no object with the same id
    if (countrySelected.value.some((item) => item.id === selectedItem.id)) {
        return;
    }

    countrySelected.value.push(selectedItem);

    handleGetPosts(
        route(props.pathList, {
            page: 1,
            search_query: searchForm.search_query,
            type: typeSelected.value,
            category: categorySelected.value,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};
// handle country # end

// handle state # start
const handleRemoveState = function (selectedItem) {
    if (!selectedItem) {
        return;
    }

    // Find the index of the selected item in categorySelected.value
    const index = stateSelected.value.findIndex(
        (item) => item.id === selectedItem.id
    );

    // If the item is found, remove it from the array
    if (index !== -1) {
        stateSelected.value.splice(index, 1);
    }

    handleGetPosts(
        route(props.pathList, {
            page: 1,
            search_query: searchForm.search_query,
            type: typeSelected.value,
            category: categorySelected.value,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};
const handleSelectState = function (selectedItem) {
    if (!selectedItem) {
        return;
    }
    // Check if there is no object with the same id
    if (stateSelected.value.some((item) => item.id === selectedItem.id)) {
        return;
    }

    stateSelected.value.push(selectedItem);

    handleGetPosts(
        route(props.pathList, {
            page: 1,
            search_query: searchForm.search_query,
            type: typeSelected.value,
            category: categorySelected.value,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};
// handle state # end

const goToSinglePost = function (teamSlug, postSlug, postId) {
    router.get(
        route(props.nameList + "." + "guest.show", [teamSlug, postSlug, postId])
    );
};

onMounted(() => {
    fetchComponents(1);
});
</script>

<template>
    <div class="min-h-screen">
        <FullWidthElement :descriptionArea="true" :headerArea="false">
            <template #content>
                <!-- Search # start -->
                <div>
                    <form @submit.prevent="handleSearch">
                        <div class="myPrimarySection">
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
                                        v-model="searchForm.search_query"
                                        type="search"
                                        id="search_query"
                                        class="myPrimarySearchInput"
                                        autocomplete="off"
                                        placeholder="Search..."
                                    />
                                </div>

                                <button
                                    @click="handleSearch"
                                    type="button"
                                    class="myPrimaryButton"
                                >
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- Search # end -->

                    <!-- Loading # start -->
                    <template v-if="isLoadingPosts">
                        <SmallUniversalSpinner
                            width="w-8"
                            height="h-8"
                            border="border-4"
                        ></SmallUniversalSpinner>
                    </template>
                    <!-- Loading # end -->
                </div>

                <template
                    v-if="!isLoadingPosts && !isErrorPosts && isSuccessPosts"
                >
                    <div class="grid myPrimaryGap grid-cols-2">
                        <!-- Country # start -->
                        <div
                            class="flex-1 w-full"
                            v-if="
                                countrySelected &&
                                Array.isArray(countrySelected) &&
                                fetchedDataPosts &&
                                Array.isArray(fetchedDataPosts.countries)
                            "
                        >
                            <ItemsFilterSelection
                                nameOfList="Countries"
                                :list="fetchedDataPosts.countries"
                                :listSelected="countrySelected"
                                icon="GlobeAmericasIcon"
                                @removeItem="handleRemoveCountry"
                                @selectItem="handleSelectCountry"
                            ></ItemsFilterSelection>
                        </div>
                        <!-- Country # end -->

                        <!-- State # start -->
                        <div
                            class="flex-1 w-full"
                            v-if="
                                stateSelected &&
                                Array.isArray(stateSelected) &&
                                fetchedDataPosts &&
                                Array.isArray(fetchedDataPosts.states)
                            "
                        >
                            <ItemsFilterSelection
                                nameOfList="States"
                                :list="fetchedDataPosts.states"
                                :listSelected="stateSelected"
                                icon="MapPinIcon"
                                @removeItem="handleRemoveState"
                                @selectItem="handleSelectState"
                            ></ItemsFilterSelection>
                        </div>
                        <!-- State # end -->

                        <!-- Type # start -->
                        <div
                            class="flex-1 w-full"
                            v-if="
                                typeSelected &&
                                Array.isArray(typeSelected) &&
                                fetchedDataPosts &&
                                Array.isArray(fetchedDataPosts.types)
                            "
                        >
                            <ItemsFilterSelection
                                nameOfList="Types"
                                :list="fetchedDataPosts.types"
                                :listSelected="typeSelected"
                                icon="NewspaperIcon"
                                @removeItem="handleRemoveType"
                                @selectItem="handleSelectType"
                            ></ItemsFilterSelection>
                        </div>
                        <!-- Type # end -->

                        <!-- Categories # start -->
                        <div
                            class="flex-1 w-full"
                            v-if="
                                categorySelected &&
                                Array.isArray(categorySelected) &&
                                fetchedDataPosts &&
                                Array.isArray(fetchedDataPosts.categories)
                            "
                        >
                            <ItemsFilterSelection
                                nameOfList="Categories"
                                :list="fetchedDataPosts.categories"
                                :listSelected="categorySelected"
                                icon="Squares2X2Icon"
                                @removeItem="handleRemoveCategory"
                                @selectItem="handleSelectCategory"
                            ></ItemsFilterSelection>
                        </div>
                        <!-- Categories # end -->
                    </div>

                    <!-- List Grid # start -->
                    <div v-if="fetchedDataPosts && fetchedDataPosts.posts">
                        <ul
                            v-if="
                                Array.isArray(fetchedDataPosts.posts.data) &&
                                fetchedDataPosts.posts.data.length !== 0
                            "
                            role="list"
                            class="grid myPrimaryGap lg:grid-cols-4 sm:grid-cols-2"
                        >
                            <li
                                v-for="post in fetchedDataPosts.posts.data"
                                :key="post.id"
                                class="overflow-hidden whitespace-pre-line flex-1 h-auto rounded bg-gray-100"
                            >
                                <!-- start photo -->

                                <template
                                    v-if="post && post.cover_images !== null"
                                >
                                    <ThumbnailSmallImageSlider
                                        :images="post.cover_images"
                                        imageSize="large_path"
                                        imageHeight="max-h-96"
                                        imageWidth="w-full object-cover"
                                        :roundedFull="false"
                                        :squareButtons="true"
                                        @firstButtonClick="
                                            goToSinglePost(
                                                post.team.slug,
                                                post.slug,
                                                post.id
                                            )
                                        "
                                        :imageClickable="true"
                                    ></ThumbnailSmallImageSlider>
                                </template>

                                <section class="pt-4 pb-4 px-2">
                                    <button
                                        @click="
                                            goToSinglePost(
                                                post.team.slug,
                                                post.slug,
                                                post.id
                                            )
                                        "
                                        type="button"
                                        class="w-full text-left"
                                    >
                                        <p
                                            class="text-sm font-medium mt-2 mb-2"
                                        >
                                            {{ post.title }}
                                        </p>
                                    </button>

                                    <template
                                        v-if="
                                            Array.isArray(
                                                post.team?.coverImagesWithLogos
                                                    ?.logos
                                            ) &&
                                            post.team?.coverImagesWithLogos
                                                ?.logos.length !== 0
                                        "
                                    >
                                        <ThumbnailSmallImageSlider
                                            :images="
                                                post.team?.coverImagesWithLogos
                                                    ?.logos
                                            "
                                            imageSize="medium_path"
                                            imageHeight="h-16"
                                            imageWidth="w-16"
                                            :roundedFull="true"
                                        ></ThumbnailSmallImageSlider>
                                    </template>

                                    <template
                                        v-if="
                                            Array.isArray(
                                                post.team?.coverImagesWithLogos
                                                    ?.logos
                                            ) &&
                                            post.team?.coverImagesWithLogos
                                                ?.logos.length === 0
                                        "
                                    >
                                        <div
                                            class="text-xs h-16 flex items-center justify-center font-medium"
                                        >
                                            {{ post.team.name }}
                                        </div>
                                    </template>

                                    <!-- Country # start -->
                                    <template v-if="post.countries">
                                        <ItemDisplaySelection
                                            :list="post.countries"
                                            :listSelected="countrySelected"
                                            icon="GlobeAmericasIcon"
                                            @removeItem="handleRemoveCountry"
                                            @selectItem="handleSelectCountry"
                                        ></ItemDisplaySelection>
                                    </template>
                                    <!-- Country # end -->

                                    <!-- State # start -->
                                    <template v-if="post.states">
                                        <ItemDisplaySelection
                                            :list="post.states"
                                            :listSelected="stateSelected"
                                            icon="MapPinIcon"
                                            @removeItem="handleRemoveState"
                                            @selectItem="handleSelectState"
                                        ></ItemDisplaySelection>
                                    </template>
                                    <!-- State # end -->

                                    <!-- Type # start -->
                                    <template v-if="post.types">
                                        <ItemDisplaySelection
                                            :list="post.types"
                                            :listSelected="typeSelected"
                                            icon="NewspaperIcon"
                                            @removeItem="handleRemoveType"
                                            @selectItem="handleSelectType"
                                        ></ItemDisplaySelection>
                                    </template>
                                    <!-- Type # end -->

                                    <!-- Category # start -->
                                    <template v-if="post.categories">
                                        <ItemDisplaySelection
                                            :list="post.categories"
                                            :listSelected="categorySelected"
                                            icon="Squares2X2Icon"
                                            @removeItem="handleRemoveCategory"
                                            @selectItem="handleSelectCategory"
                                        ></ItemDisplaySelection>
                                    </template>
                                    <!-- Category # end -->
                                </section>
                            </li>
                        </ul>
                    </div>
                    <!-- List Grid # end -->

                    <!-- Pagination # start -->
                    <div
                        v-if="fetchedDataPosts && fetchedDataPosts.posts"
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
                            :data="fetchedDataPosts.posts"
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
                    <!-- Pagination # end -->
                </template>
            </template>
        </FullWidthElement>
    </div>
</template>
