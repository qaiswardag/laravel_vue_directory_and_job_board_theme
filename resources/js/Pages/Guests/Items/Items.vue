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

const typeSelected = ref({ name: "All", id: null });
const categorySelected = ref({ name: "All", id: null });
const countrySelected = ref({ name: "All", id: null });
const stateSelected = ref({ name: "All", id: null });

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

// get result for "laravel pagination" package
const getResultsForPage = (page = 1) => {
    fetchComponents(page);
};

// handle type
const handleJobType = function (type) {
    typeSelected.value = type;

    handleGetPosts(
        route(props.pathList, {
            page: 1,
            search_query: searchForm.search_query,
            type: type,
            category: categorySelected.value,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};
// handle categroy
const handleCategory = function (category) {
    categorySelected.value = category;

    handleGetPosts(
        route(props.pathList, {
            page: 1,
            search_query: searchForm.search_query,
            type: typeSelected.value,
            category: category,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};

// handle country
const handleCountry = function (country) {
    countrySelected.value = country;

    handleGetPosts(
        route(props.pathList, {
            page: 1,
            search_query: searchForm.search_query,
            type: typeSelected.value,
            category: categorySelected.value,
            country: country,
            state: stateSelected.value,
        })
    );
};

// handle state
const handleState = function (state) {
    stateSelected.value = state;

    handleGetPosts(
        route(props.pathList, {
            page: 1,
            search_query: searchForm.search_query,
            type: typeSelected.value,
            category: categorySelected.value,
            country: countrySelected.value,
            state: state,
        })
    );
};

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
    <div>
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
                    <!-- Country # Start -->
                    <template
                        v-if="
                            fetchedDataPosts &&
                            fetchedDataPosts &&
                            Array.isArray(fetchedDataPosts.countries)
                        "
                    >
                        <div class="my-12 myPrimaryDarkGrayColor">
                            <p class="text-xs">Countries</p>
                            <div
                                class="flex gap-2 flex-wrap py-4 border-t border-b border-gray-200"
                            >
                                <button
                                    @click="
                                        handleCountry({
                                            name: 'All',
                                            id: null,
                                        })
                                    "
                                    class="myPrimaryTag"
                                    :class="[
                                        {
                                            'bg-myPrimaryLinkColor text-white':
                                                countrySelected.name === 'All',
                                        },
                                    ]"
                                    :disabled="countrySelected.name === 'All'"
                                >
                                    All
                                </button>
                                <template
                                    v-for="category in fetchedDataPosts.countries"
                                    :key="category.id"
                                >
                                    <button
                                        @click="
                                            handleCountry({
                                                name: category.name,
                                                id: category.id,
                                            })
                                        "
                                        class="myPrimaryTag"
                                        :class="[
                                            {
                                                'bg-myPrimaryLinkColor text-white':
                                                    countrySelected.name ===
                                                    category.name,
                                            },
                                            {
                                                'bg-myPrimaryLinkColor text-white':
                                                    countrySelected.name ===
                                                    category.name,
                                            },
                                        ]"
                                        :disabled="
                                            countrySelected.name ===
                                            category.name
                                        "
                                    >
                                        <div
                                            class="flex justify-center items-center gap-1"
                                        >
                                            <GlobeAmericasIcon
                                                class="mySmallIcon py-0 m-0"
                                            ></GlobeAmericasIcon>
                                            {{ category.name }}
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                    <!-- Country # End -->

                    <!-- States # Start -->
                    <template
                        v-if="
                            fetchedDataPosts &&
                            fetchedDataPosts &&
                            Array.isArray(fetchedDataPosts.states)
                        "
                    >
                        <div class="my-12 myPrimaryDarkGrayColor">
                            <p class="text-xs">States</p>
                            <div
                                class="flex gap-2 flex-wrap py-4 border-t border-b border-gray-200"
                            >
                                <button
                                    @click="
                                        handleState({
                                            name: 'All',
                                            id: null,
                                        })
                                    "
                                    class="myPrimaryTag"
                                    :class="[
                                        {
                                            'bg-myPrimaryLinkColor text-white':
                                                stateSelected.name === 'All',
                                        },
                                    ]"
                                    :disabled="stateSelected.name === 'All'"
                                >
                                    All
                                </button>
                                <template
                                    v-for="category in fetchedDataPosts.states"
                                    :key="category.id"
                                >
                                    <button
                                        @click="
                                            handleState({
                                                name: category.name,
                                                id: category.id,
                                            })
                                        "
                                        class="myPrimaryTag"
                                        :class="[
                                            {
                                                'bg-myPrimaryLinkColor text-white':
                                                    stateSelected.name ===
                                                    category.name,
                                            },
                                            {
                                                'bg-myPrimaryLinkColor text-white':
                                                    stateSelected.name ===
                                                    category.name,
                                            },
                                        ]"
                                        :disabled="
                                            stateSelected.name === category.name
                                        "
                                    >
                                        <div
                                            class="flex justify-center items-center gap-1"
                                        >
                                            <MapPinIcon
                                                class="mySmallIcon py-0 m-0"
                                            ></MapPinIcon>
                                            {{ category.name }}
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                    <!-- State # End -->

                    <!-- Types # Start -->
                    <template
                        v-if="
                            fetchedDataPosts &&
                            fetchedDataPosts &&
                            Array.isArray(fetchedDataPosts.types)
                        "
                    >
                        <div class="my-12 myPrimaryDarkGrayColor">
                            <p class="text-xs">Job type</p>
                            <div
                                class="flex gap-2 flex-wrap py-4 border-t border-b border-gray-200"
                            >
                                <button
                                    @click="
                                        handleJobType({
                                            name: 'All',
                                            id: null,
                                        })
                                    "
                                    class="myPrimaryTag"
                                    :class="[
                                        {
                                            'bg-myPrimaryLinkColor text-white':
                                                typeSelected.name === 'All',
                                        },
                                    ]"
                                    :disabled="typeSelected.name === 'All'"
                                >
                                    All
                                </button>
                                <template
                                    v-for="category in fetchedDataPosts.types"
                                    :key="category.id"
                                >
                                    <button
                                        @click="
                                            handleJobType({
                                                name: category.name,
                                                id: category.id,
                                            })
                                        "
                                        class="myPrimaryTag"
                                        :class="[
                                            {
                                                'bg-myPrimaryLinkColor text-white':
                                                    typeSelected.name ===
                                                    category.name,
                                            },
                                            {
                                                'bg-myPrimaryLinkColor text-white':
                                                    typeSelected.name ===
                                                    category.name,
                                            },
                                        ]"
                                        :disabled="
                                            typeSelected.name === category.name
                                        "
                                    >
                                        <div
                                            class="flex justify-center items-center gap-1"
                                        >
                                            <NewspaperIcon
                                                class="mySmallIcon py-0 m-0"
                                            ></NewspaperIcon>
                                            {{ category.name }}
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                    <!-- Types # End -->

                    <!-- Categories # Start -->
                    <template
                        v-if="
                            fetchedDataPosts &&
                            fetchedDataPosts &&
                            Array.isArray(fetchedDataPosts.categories)
                        "
                    >
                        <div class="my-12 myPrimaryDarkGrayColor">
                            <p class="text-xs">Categories</p>
                            <div
                                class="flex gap-2 flex-wrap py-4 border-t border-b border-gray-200"
                            >
                                <button
                                    @click="
                                        handleCategory({
                                            name: 'All',
                                            id: null,
                                        })
                                    "
                                    class="myPrimaryTag"
                                    :class="[
                                        {
                                            'bg-myPrimaryLinkColor text-white':
                                                categorySelected.name === 'All',
                                        },
                                    ]"
                                    :disabled="categorySelected.name === 'All'"
                                >
                                    All
                                </button>
                                <template
                                    v-for="category in fetchedDataPosts.categories"
                                    :key="category.id"
                                >
                                    <button
                                        @click="
                                            handleCategory({
                                                name: category.name,
                                                id: category.id,
                                            })
                                        "
                                        class="myPrimaryTag"
                                        :class="[
                                            {
                                                'bg-myPrimaryLinkColor text-white':
                                                    categorySelected.name ===
                                                    category.name,
                                            },
                                            {
                                                'bg-myPrimaryLinkColor text-white':
                                                    categorySelected.name ===
                                                    category.name,
                                            },
                                        ]"
                                        :disabled="
                                            categorySelected.name ===
                                            category.name
                                        "
                                    >
                                        <div
                                            class="flex justify-center items-center gap-1"
                                        >
                                            <Squares2X2Icon
                                                class="mySmallIcon py-0 m-0"
                                            ></Squares2X2Icon>
                                            {{ category.name }}
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                    <!-- Categories # End -->

                    <!-- List Grid # start -->
                    <div v-if="fetchedDataPosts && fetchedDataPosts.posts">
                        <ul
                            v-if="
                                Array.isArray(fetchedDataPosts.posts.data) &&
                                fetchedDataPosts.posts.data.length !== 0
                            "
                            role="list"
                            class="grid myPrimaryGap md:grid-cols-2 grid-cols-2"
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

                                <section class="pt-4 pb-4 px-4">
                                    <button
                                        @click="
                                            goToSinglePost(
                                                post.team.slug,
                                                post.slug,
                                                post.id
                                            )
                                        "
                                        type="button"
                                    >
                                        <p
                                            class="text-sm font-medium mt-2 mb-2"
                                        >
                                            {{ post.title }}
                                        </p>
                                    </button>

                                    <!-- Country # start -->
                                    <template v-if="post.countries">
                                        <div
                                            class="my-4 flex flex-wrap gap-2 items-center justify-left"
                                        >
                                            <button
                                                @click="
                                                    handleCountry({
                                                        name: category.name,
                                                        id: category.id,
                                                    })
                                                "
                                                v-for="category in post.countries"
                                                :key="category.id"
                                                class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                                                :class="[
                                                    {
                                                        'bg-myPrimaryLinkColor text-white':
                                                            countrySelected.name ===
                                                            category.name,
                                                    },
                                                    {
                                                        'bg-myPrimaryLinkColor text-white':
                                                            countrySelected.name ===
                                                            category.name,
                                                    },
                                                ]"
                                                :disabled="
                                                    countrySelected.name ===
                                                    category.name
                                                "
                                            >
                                                <GlobeAmericasIcon
                                                    class="w-3 h-3 stroke-1"
                                                ></GlobeAmericasIcon>
                                                {{ category.name }}
                                            </button>
                                        </div>
                                    </template>
                                    <!-- Country # end -->

                                    <!-- State # start -->
                                    <template v-if="post.states">
                                        <div
                                            class="my-4 flex flex-wrap gap-2 items-center justify-left"
                                        >
                                            <button
                                                @click="
                                                    handleState({
                                                        name: category.name,
                                                        id: category.id,
                                                    })
                                                "
                                                v-for="category in post.states"
                                                :key="category.id"
                                                class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                                                :class="[
                                                    {
                                                        'bg-myPrimaryLinkColor text-white':
                                                            stateSelected.name ===
                                                            category.name,
                                                    },
                                                    {
                                                        'bg-myPrimaryLinkColor text-white':
                                                            stateSelected.name ===
                                                            category.name,
                                                    },
                                                ]"
                                                :disabled="
                                                    stateSelected.name ===
                                                    category.name
                                                "
                                            >
                                                <MapPinIcon
                                                    class="w-3 h-3 stroke-1"
                                                ></MapPinIcon>
                                                {{ category.name }}
                                            </button>
                                        </div>
                                    </template>
                                    <!-- State # end -->

                                    <!-- Type # start -->
                                    <template v-if="post.types">
                                        <div
                                            class="my-4 flex flex-wrap gap-2 items-center justify-left"
                                        >
                                            <button
                                                @click="
                                                    handleJobType({
                                                        name: category.name,
                                                        id: category.id,
                                                    })
                                                "
                                                v-for="category in post.types"
                                                :key="category.id"
                                                class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                                                :class="[
                                                    {
                                                        'bg-myPrimaryLinkColor text-white':
                                                            typeSelected.name ===
                                                            category.name,
                                                    },
                                                    {
                                                        'bg-myPrimaryLinkColor text-white':
                                                            typeSelected.name ===
                                                            category.name,
                                                    },
                                                ]"
                                                :disabled="
                                                    typeSelected.name ===
                                                    category.name
                                                "
                                            >
                                                <NewspaperIcon
                                                    class="w-3 h-3 stroke-1"
                                                ></NewspaperIcon>
                                                {{ category.name }}
                                            </button>
                                        </div>
                                    </template>
                                    <!-- Type # end -->

                                    <!-- Category # start -->
                                    <template v-if="post.categories">
                                        <div
                                            class="my-4 flex flex-wrap gap-2 items-center justify-left"
                                        >
                                            <button
                                                @click="
                                                    handleCategory({
                                                        name: category.name,
                                                        id: category.id,
                                                    })
                                                "
                                                v-for="category in post.categories"
                                                :key="category.id"
                                                class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                                                :class="[
                                                    {
                                                        'bg-myPrimaryLinkColor text-white':
                                                            categorySelected.name ===
                                                            category.name,
                                                    },
                                                    {
                                                        'bg-myPrimaryLinkColor text-white':
                                                            categorySelected.name ===
                                                            category.name,
                                                    },
                                                ]"
                                                :disabled="
                                                    categorySelected.name ===
                                                    category.name
                                                "
                                            >
                                                <Squares2X2Icon
                                                    class="w-3 h-3 stroke-1"
                                                ></Squares2X2Icon>
                                                {{ category.name }}
                                            </button>
                                        </div>
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

                    <p class="my-12">
                        List er:
                        {{ JSON.stringify(fetchedDataPosts?.posts?.data) }}
                    </p>
                </template>
            </template>
        </FullWidthElement>
    </div>
</template>
