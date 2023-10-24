<script setup>
import { useStore } from "vuex";
import { vueFetch } from "use-lightweight-fetch";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import { onMounted, ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";

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

// get images
const {
    handleData: handleGetList,
    fetchedData: fetchedDataList,
    isError: isErrorList,
    error: errorList,
    errors: errorsList,
    isLoading: isLoadingList,
    isSuccess: isSuccessList,
} = vueFetch();

const getList = function () {
    handleGetList(route(props.pathList));
};

// form
const searchForm = useForm({
    search_query: "",
});

const handleSearch = function () {
    handleGetList(
        route(props.pathList, {
            search_query: searchForm.search_query,
        })
    );
};

const categorySelected = ref({ name: "All", id: null });

const handleCategory = function (category) {
    console.log(`handle cate..`);
    return;
    categorySelected.value = category;

    //
    //
    // dispatch
    store.dispatch("pageBuilderState/loadComponents", {
        team: props.team,
        page: 1,
        search_query: search_query.value,
        category: category,
    });
};

const goToSinglePost = function (current_teamId, postId, postSlug) {
    router.get(
        route(props.nameList + "." + "guest.show", [
            current_teamId,
            postId,
            postSlug,
        ])
    );
};

onMounted(() => {
    getList();

    if (props.oldInput?.search_query) {
        searchForm.search_query = props.oldInput.search_query;
    }
});
</script>

<template>
    <div>
        <FullWidthElement
            :descriptionArea="true"
            class="bg-red-50 min-h-screen ease-in duration-300"
        >
            <template #description></template>

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
                    <template v-if="isLoadingList">
                        <SmallUniversalSpinner
                            width="w-8"
                            height="h-8"
                            border="border-4"
                        ></SmallUniversalSpinner>
                    </template>
                    <!-- Loading # end -->
                </div>

                <template
                    v-if="!isLoadingList && !isErrorList && isSuccessList"
                >
                    <!-- List Grid # start -->
                    <div v-if="fetchedDataList && fetchedDataList.posts">
                        <ul
                            v-if="
                                Array.isArray(fetchedDataList.posts.data) &&
                                fetchedDataList.posts.data.length !== 0
                            "
                            role="list"
                            class="grid myPrimaryGap md:grid-cols-2 grid-cols-2"
                        >
                            <transition-group name="fade">
                                <li
                                    v-for="post in fetchedDataList.posts.data"
                                    :key="post.id"
                                    class="overflow-hidden whitespace-pre-line flex-1 h-auto rounded pb-2"
                                >
                                    <!-- start photo -->

                                    <template
                                        v-if="
                                            post && post.cover_images !== null
                                        "
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
                                                    $page.props.user
                                                        .current_team.id,
                                                    post.id,
                                                    post.slug
                                                )
                                            "
                                            :imageClickable="true"
                                        ></ThumbnailSmallImageSlider>
                                    </template>

                                    <button
                                        @click="
                                            goToSinglePost(
                                                $page.props.user.current_team
                                                    .id,
                                                post.id,
                                                post.slug
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

                                    <!-- Category # start -->
                                    <div
                                        class="flex flex-wrap gap-2 items-center justify-left"
                                    >
                                        <button
                                            @click="
                                                handleCategory({
                                                    name: category.name,
                                                    id: category.id,
                                                })
                                            "
                                            v-for="category in post.categories &&
                                            post.categories"
                                            :key="category.id"
                                            class="myPrimaryTag text-[10px] py-2 px-2"
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
                                            {{ category.name }}
                                        </button>
                                    </div>
                                    <!-- Category # end -->
                                </li>
                            </transition-group>
                        </ul>
                    </div>
                    <!-- List Grid # end -->

                    <p class="my-12">
                        List er:
                        {{ JSON.stringify(fetchedDataList?.posts?.data) }}
                    </p>
                </template>
            </template>
        </FullWidthElement>
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
