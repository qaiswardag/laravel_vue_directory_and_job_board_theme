<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import { ref, computed, onMounted, onBeforeMount, nextTick } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";
import componentHelpers from "@/utils/builder/html-elements/componentHelpers";
import {
    Squares2X2Icon,
    TrashIcon,
    CheckIcon,
    MapPinIcon,
    GlobeAmericasIcon,
    PlusIcon,
    MinusIcon,
    NewspaperIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import PageBuilder from "@/composables/PageBuilder";
import Spinner from "@/Components/PageBuilder/Loaders/Spinner.vue";

import { useStore } from "vuex";

const props = defineProps({
    team: {
        required: true,
    },
    firstButtonText: {
        required: true,
    },
    title: {
        required: true,
    },
    show: {
        type: Boolean,
        default: false,
        required: true,
    },
});

const emit = defineEmits(["firstModalButtonSearchComponentsFunction"]);

// first button function
const firstButton = function () {
    emit("firstModalButtonSearchComponentsFunction");
};

const search_query = ref("");
const categorySelected = ref({ name: "All", id: null });
const store = useStore();
const pageBuilder = new PageBuilder(store);

// use dynamic model
const getComponents = computed(() => {
    return store.getters["pageBuilderState/getComponents"];
});
const getFetchedComponents = computed(() => {
    return store.getters["pageBuilderState/getFetchedComponents"];
});

const handleDropComponent = async function (componentObject) {
    await nextTick();
    const clonedComponent = pageBuilder.cloneCompObjForDOMInsertion({
        html_code: componentObject.html_code,
        id: componentObject.id,
    });

    await nextTick();
    store.commit("pageBuilderState/setPushComponents", clonedComponent);

    await nextTick();
    pageBuilder.setEventListenersForElements();

    // Close modal
    firstButton();
};

const handleAddHelperComponent = async function (helperComponentObject) {
    await nextTick();
    const clonedComponent = pageBuilder.cloneCompObjForDOMInsertion({
        html_code: helperComponentObject.html_code,
        id: helperComponentObject.id,
    });

    await nextTick();
    store.commit("pageBuilderState/setPushComponents", clonedComponent);

    await nextTick();
    pageBuilder.setEventListenersForElements();

    // Close modal
    firstButton();
};

//
//
//
// handle search
const handleSearch = function (page) {
    // dispatch
    store.dispatch("pageBuilderState/loadComponents", {
        team: props.team,
        page: page,
        search_query: search_query.value,
        category: categorySelected.value,
    });
};
//
// get media
const fetchComponents = function (page) {
    // dispatch
    store.dispatch("pageBuilderState/loadComponents", {
        team: props.team,
        page: page,
        search_query: search_query.value,
        category: categorySelected.value,
    });
};

// get result for "laravel pagination" package
const getResultsForPage = (page = 1) => {
    search_query.value = getFetchedComponents.value?.fetchedData?.search_query;
    fetchComponents(page);
};
//
//
//
const handleCategory = function (category) {
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
//
onMounted(async () => {
    fetchComponents(1);
});
</script>

<template>
    <Modal
        maxWidth="5xl"
        :show="show"
        @close="firstButton"
        minHeight="min-h-[50rem]"
        maxHeight="max-h-[50rem]"
    >
        <Spinner
            v-if="
                getFetchedComponents.isLoading && !getFetchedComponents.isError
            "
        ></Spinner>
        <div
            class="px-4 w-full relative inline-block align-bottom text-left overflow-hidden transform transition-all sm:align-middle"
        >
            <div
                class="flex items-center border-b border-gray-200 pb-2 mb-2 justify-between"
            >
                <h3 class="tertiaryHeader my-0 py-0">
                    {{ title }}
                </h3>
                <div @click="firstButton" class="flex-end">
                    <XMarkIcon
                        class="h-6 w-6 text-myPrimaryDarkGrayColor self-center cursor-pointer"
                    ></XMarkIcon>
                </div>
            </div>

            <!-- content start -->
            <div
                v-if="getFetchedComponents.isError"
                class="min-h-[2.5rem] flex items-center justify-start"
            >
                <p class="myPrimaryInputError mt-2 mb-0 py-0 self-start">
                    {{ getFetchedComponents.error }}
                </p>
            </div>
            <main
                v-if="
                    !getFetchedComponents.isError &&
                    !getFetchedComponents.isLoading
                "
            >
                <!-- Search # Start -->
                <form @submit.prevent="handleSearch(1)">
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
                </form>
                <!-- Search # End -->

                <!-- Categories # Start -->
                <div class="flex gap-2 flex-wrap">
                    <button
                        @click="handleCategory({ name: 'All', id: null })"
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
                        v-for="category in getFetchedComponents &&
                        getFetchedComponents.fetchedData &&
                        Array.isArray(
                            getFetchedComponents.fetchedData
                                .component_categories
                        ) &&
                        getFetchedComponents.fetchedData.component_categories"
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
                                        categorySelected.name === category.name,
                                },
                                {
                                    'bg-myPrimaryLinkColor text-white':
                                        categorySelected.name === category.name,
                                },
                            ]"
                            :disabled="categorySelected.name === category.name"
                        >
                            {{ category.name }}
                        </button>
                    </template>
                </div>
                <!-- Categories # End -->

                <div
                    v-if="
                        getFetchedComponents &&
                        getFetchedComponents.fetchedData &&
                        getFetchedComponents.fetchedData.components
                    "
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
                        :data="getFetchedComponents.fetchedData.components"
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
                    class="h-full flex md:flex-row flex-col myPrimaryGap mt-2 p-2 overflow-y-scroll"
                >
                    <section class="md:w-4/6">
                        <div
                            class="overflow-scroll min-h-[30rem] max-h-[30rem] grid myPrimaryGap md:grid-cols-2 grid-cols-2 w-full gap-2 px-2 p-4 border border-myPrimaryLightGrayColor"
                        >
                            <div
                                class="px-4 py-4 bg-gray-50"
                                v-for="component in getFetchedComponents &&
                                getFetchedComponents.fetchedData &&
                                getFetchedComponents.fetchedData.components &&
                                Array.isArray(
                                    getFetchedComponents.fetchedData.components
                                        .data
                                ) &&
                                getFetchedComponents.fetchedData.components
                                    .data"
                                :key="component.id"
                            >
                                <div>
                                    <template
                                        v-if="
                                            component && component.cover_images
                                        "
                                    >
                                        <ThumbnailSmallImageSlider
                                            :images="component.cover_images"
                                            imageSize="medium_path"
                                            imageHeight="h-auto"
                                            imageWidth="w-full object-cover"
                                            :roundedFull="false"
                                            :squareButtons="true"
                                        ></ThumbnailSmallImageSlider>
                                    </template>
                                </div>

                                <div class="px-2 pt-4 pb-2">
                                    <div v-if="!component.published">
                                        <p
                                            class="inline-block text-myPrimaryErrorColor text-xs font-medium"
                                        >
                                            Unpublished
                                        </p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="handleDropComponent(component)"
                                        class="text-sm font-medium mt-2 mb-2 text-myPrimaryLinkColor inline-block text-left"
                                    >
                                        {{ component.title }}
                                    </button>

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
                                            v-for="category in component.categories &&
                                            component.categories"
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

                                    <div
                                        class="py-4 mt-4 border-t border-gray-200"
                                    >
                                        <button
                                            type="button"
                                            @click="
                                                handleDropComponent(component)
                                            "
                                            class="myPrimaryButton text-xs"
                                        >
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <aside
                        class="md:w-2/6 overflow-scroll min-h-[30rem] max-h-[30rem] w-full"
                    >
                        <div
                            class="p-2 flex flex-col w-full overflow-y-scroll border border-myPrimaryLightGrayColor divide-y divide-gray-200"
                        >
                            <!-- Unique HTML Component # start -->
                            <div
                                @click="
                                    handleAddHelperComponent(helperComponent)
                                "
                                v-for="helperComponent in componentHelpers"
                                :key="helperComponent.title"
                                class="flex justify-between items-center gap-4 text-xs font-medium py-4 hover:bg-gray-50 cursor-pointer px-2"
                            >
                                <button
                                    type="button"
                                    class="flex items-left gap-2 my-2 cursor-pointer"
                                >
                                    {{ helperComponent.title }}
                                </button>
                                <button
                                    @click="
                                        handleAddHelperComponent(
                                            helperComponent
                                        )
                                    "
                                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                                >
                                    <PlusIcon
                                        class="shrink-0 w-4 h-4 m-2 stroke-2"
                                    ></PlusIcon>
                                </button>
                            </div>
                        </div>
                    </aside>
                </div>
            </main>
            <!-- content end -->
        </div>
        <div
            v-if="!getFetchedComponents.isError"
            class="bg-gray-100 px-6 py-4 absolute bottom-0 left-0 right-0 flex sm:justify-end justify-center"
        >
            <div class="sm:w-3/6 w-full px-2 my-2 flex gap-2 justify-end">
                <button
                    v-if="firstButtonText"
                    ref="firstButtonRef"
                    class="mySecondaryButton"
                    type="button"
                    @click="firstButton"
                >
                    {{ firstButtonText }}
                </button>
            </div>
        </div>
    </Modal>
</template>
