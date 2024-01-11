<script setup>
import { useStore } from "vuex";
import { vueFetch } from "@/composables/vueFetch";
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
import { parseISO, format } from "date-fns";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";

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
    tags_or_content: false,
    search_query: "",
});

const searchTagsOrContent = function () {
    searchForm.tags_or_content = !searchForm.tags_or_content;

    handleGetPosts(
        route(props.pathList, {
            page: 1,
            search_query: searchForm.search_query,
            tags_or_content: searchForm.tags_or_content,
            type: typeSelected.value,
            category: categorySelected.value,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};

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
            tags_or_content: searchForm.tags_or_content,
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
            tags_or_content: searchForm.tags_or_content,
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
            tags_or_content: searchForm.tags_or_content,
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
            tags_or_content: searchForm.tags_or_content,
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
            tags_or_content: searchForm.tags_or_content,
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
            tags_or_content: searchForm.tags_or_content,
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
            tags_or_content: searchForm.tags_or_content,
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
            tags_or_content: searchForm.tags_or_content,
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
            tags_or_content: searchForm.tags_or_content,
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
            tags_or_content: searchForm.tags_or_content,
            type: typeSelected.value,
            category: categorySelected.value,
            country: countrySelected.value,
            state: stateSelected.value,
        })
    );
};
// handle state # end

const goToSinglePost = function (teamSlug, postSlug, postId) {
    const url = route(props.nameList + "." + "guest.show", [
        teamSlug,
        postSlug,
        postId,
    ]);

    // Open the URL in a new tab
    window.open(url, "_blank");
};

onMounted(() => {
    fetchComponents(1);
});
</script>

<template>
    <div class="min-h-screen">
        <FullWidthElement :descriptionArea="true" :headerArea="false">
            <template #content>
                <div
                    class="flex flex-col gap-2 border-b border-gray-200 pb-2 mb-8"
                >
                    <!-- error # start -->
                    <template v-if="!isLoadingPosts && isErrorPosts">
                        <p class="myPrimaryParagraphError">
                            {{ errorPosts }}
                        </p>
                    </template>
                    <!-- error # end -->

                    <!-- Search in Tags Or Content # start -->
                    <div
                        class="border-b border-gray-200 pb-2"
                        v-if="fetchedDataPosts && fetchedDataPosts.posts"
                    >
                        <!-- options dropdow # start -->
                        <div>
                            <Menu
                                as="div"
                                class="relative inline-block text-left"
                            >
                                <div>
                                    <MenuButton
                                        class="myPrimaryParagraph pl-3 pr-3 py-2 text-xs font-medium inline-flex w-full items-center justify-center gap-2 rounded-md bg-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                                    >
                                        <span class="material-symbols-outlined">
                                            filter_alt
                                        </span>
                                        <span> Filter </span>
                                        <span class="material-symbols-outlined">
                                            arrow_drop_down
                                        </span>
                                    </MenuButton>
                                </div>

                                <transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                                >
                                    <MenuItems
                                        class="absolute left-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    >
                                        <div class="py-1">
                                            <MenuItem>
                                                <button
                                                    type="button"
                                                    @click="searchTagsOrContent"
                                                    class="pl-4 pr-2 pt-2 pb-2 w-full text-sm flex items-center justify-start gap-2 my-4 hover:bg-myPrimaryLightGrayColor"
                                                    :class="[
                                                        {
                                                            'bg-myPrimaryLightGrayColor':
                                                                searchForm.tags_or_content,
                                                        },
                                                        {
                                                            '': !searchForm.tags_or_content,
                                                        },
                                                    ]"
                                                >
                                                    <span>
                                                        Search in Tags & Content
                                                    </span>
                                                    <template
                                                        v-if="
                                                            searchForm.tags_or_content
                                                        "
                                                    >
                                                        <span
                                                            class="material-symbols-outlined"
                                                        >
                                                            check
                                                        </span>
                                                    </template>
                                                </button>
                                            </MenuItem>
                                        </div>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                    <!-- options dropdow # end -->
                    <!-- Search in Tags Or Content # end -->

                    <div class="flex md:flex-row flex-col myPrimaryGap">
                        <div
                            v-if="fetchedDataPosts && fetchedDataPosts.posts"
                            class="w-full"
                            :class="[
                                {
                                    'md:w-1/3': nameList !== 'posts',
                                },
                                {
                                    'md:w-1/2': nameList === 'posts',
                                },
                            ]"
                        >
                            <!-- Search # start -->

                            <form @submit.prevent="handleSearch">
                                <div class="relative w-full">
                                    <div
                                        class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"
                                    >
                                        <span class="material-symbols-outlined">
                                            search
                                        </span>
                                    </div>
                                    <input
                                        v-model="searchForm.search_query"
                                        type="search"
                                        id="search_query"
                                        class="myPrimaryInput pl-10 shadow-none min-h-[3.5rem] h-[3.5rem]"
                                        autocomplete="off"
                                        :placeholder="`${
                                            nameList === 'stores'
                                                ? 'Company or Brand name'
                                                : `Search ${nameList}`
                                        }..`"
                                    />
                                </div>
                            </form>

                            <!-- Search # end -->
                        </div>
                        <!-- Categories # start -->
                        <div
                            class="w-full"
                            :class="[
                                {
                                    'md:w-1/3': nameList !== 'posts',
                                },
                                {
                                    'md:w-1/2': nameList === 'posts',
                                },
                            ]"
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
                                icon="interests"
                                @removeItem="handleRemoveCategory"
                                @selectItem="handleSelectCategory"
                            ></ItemsFilterSelection>
                        </div>
                        <!-- Categories # end -->

                        <!-- State # start -->
                        <div
                            class="md:w-1/3 w-full"
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
                    </div>

                    <template
                        v-if="
                            (countrySelected &&
                                Array.isArray(countrySelected) &&
                                fetchedDataPosts &&
                                Array.isArray(fetchedDataPosts.countries)) ||
                            (typeSelected &&
                                Array.isArray(typeSelected) &&
                                fetchedDataPosts &&
                                Array.isArray(fetchedDataPosts.types))
                        "
                    >
                        <div class="flex md:flex-row flex-col myPrimaryGap">
                            <!-- Country # start -->
                            <div
                                class="md:w-1/2 w-full"
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

                            <!-- Type # start -->
                            <div
                                class="md:w-1/2 w-full"
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
                        </div>
                    </template>
                </div>

                <!-- If posts is empty array # start -->
                <template
                    v-if="
                        fetchedDataPosts &&
                        fetchedDataPosts.posts &&
                        Array.isArray(fetchedDataPosts.posts.data) &&
                        fetchedDataPosts.posts.data.length === 0 &&
                        !isLoadingPosts
                    "
                >
                    <h1 class="myTertiaryHeader text-center">
                        No {{ nameList === "posts" ? "campaigns" : nameList }}
                    </h1>
                    <p class="myPrimaryParagraph text-center">
                        Looks like there are no
                        {{ nameList === "posts" ? "campaigns" : nameList }}!
                    </p>
                </template>
                <!-- If posts is empty array # end -->

                <!-- Loading # start -->
                <template v-if="isLoadingPosts">
                    <SmallUniversalSpinner
                        width="w-8"
                        height="h-8"
                        border="border-4"
                    ></SmallUniversalSpinner>
                </template>
                <!-- Loading # end -->

                <template
                    v-if="!isLoadingPosts && !isErrorPosts && isSuccessPosts"
                >
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
                            <TransitionGroup>
                                <li
                                    v-for="post in fetchedDataPosts.posts.data"
                                    :key="post.id"
                                    class="overflow-hidden whitespace-pre-line flex-1 h-auto rounded bg-myPrimaryLightGrayColor"
                                >
                                    <!-- start photo -->

                                    <template
                                        v-if="
                                            post && post.cover_images !== null
                                        "
                                    >
                                        <ThumbnailSmallImageSlider
                                            :images="post.cover_images"
                                            :isLoading="isLoadingPosts"
                                            imageSize="medium_path"
                                            imageHeight="max-h-96"
                                            imageWidth="w-full object-cover rounded-b-none"
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

                                    <section class="pt-8 pb-4 px-4">
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
                                            <p class="myQuaternaryHeader">
                                                {{ post.title }}
                                            </p>
                                        </button>

                                        <template
                                            v-if="
                                                Array.isArray(
                                                    post.team
                                                        ?.coverImagesWithLogos
                                                        ?.logos
                                                ) &&
                                                post.team?.coverImagesWithLogos
                                                    ?.logos.length !== 0
                                            "
                                        >
                                            <div class="my-2">
                                                <ThumbnailSmallImageSlider
                                                    :images="
                                                        post.team
                                                            ?.coverImagesWithLogos
                                                            ?.logos
                                                    "
                                                    :isLoading="isLoadingPosts"
                                                    imageSize="medium_path"
                                                    imageHeight="h-16"
                                                    imageWidth="w-16"
                                                    :roundedFull="false"
                                                ></ThumbnailSmallImageSlider>
                                            </div>
                                        </template>

                                        <template
                                            v-if="
                                                post.started_at &&
                                                post.ended_at &&
                                                nameList === 'posts'
                                            "
                                        >
                                            <div class="flex gap-4">
                                                <div
                                                    class="myPrimaryTag text-[10px] py-1 px-2 flex items-center min-h-[1rem]"
                                                >
                                                    From:
                                                    <span class="font-medium">
                                                        {{
                                                            format(
                                                                parseISO(
                                                                    post.started_at
                                                                ),
                                                                "dd. MMMM yyyy"
                                                            )
                                                        }}
                                                    </span>
                                                </div>

                                                <div
                                                    class="myPrimaryTag text-[10px] py-1 px-2 flex items-center min-h-[1rem]"
                                                >
                                                    To:
                                                    <span class="font-medium">
                                                        {{
                                                            format(
                                                                parseISO(
                                                                    post.ended_at
                                                                ),
                                                                "dd. MMMM yyyy"
                                                            )
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                        </template>

                                        <template
                                            v-if="
                                                Array.isArray(
                                                    post.team
                                                        ?.coverImagesWithLogos
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
                                                @removeItem="
                                                    handleRemoveCountry
                                                "
                                                @selectItem="
                                                    handleSelectCountry
                                                "
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
                                                icon="interests"
                                                @removeItem="
                                                    handleRemoveCategory
                                                "
                                                @selectItem="
                                                    handleSelectCategory
                                                "
                                            ></ItemDisplaySelection>
                                        </template>
                                        <!-- Category # end -->
                                    </section>
                                </li>
                            </TransitionGroup>
                        </ul>
                    </div>
                    <!-- List Grid # end -->

                    <!-- Pagination # start -->
                    <div
                        v-if="fetchedDataPosts && fetchedDataPosts.posts"
                        class="flex items-center justify-center bg-white py-3 mt-4 gap-2"
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
    <template v-if="nameList === 'jobs'">
        <div class="lg:flex items-center justify-center hidden py-8 bg-red-50">
            <Link :href="route('jobs.fetch')">
                <p class="myPrimaryParagraph text-xs">
                    Fetch our jobs from: /api/jobs
                </p>
            </Link>
        </div>
    </template>
</template>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}
</style>
