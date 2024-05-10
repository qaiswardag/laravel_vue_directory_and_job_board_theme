<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import { ref, computed, onMounted, onBeforeMount, nextTick } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";
import componentHelpers from "@/utils/builder/html-elements/componentHelpers";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import PageBuilder from "@/composables/PageBuilder";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";

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

const getComponentArrayAddMethod = computed(() => {
    return store.getters["pageBuilderState/getComponentArrayAddMethod"];
});

const handleDropComponent = async function (componentObject) {
    await nextTick();
    const clonedComponent = pageBuilder.cloneCompObjForDOMInsertion({
        html_code: componentObject.html_code,
        id: componentObject.id,
    });

    await nextTick();

    store.commit("pageBuilderState/setPushComponents", {
        component: clonedComponent,
        componentArrayAddMethod: getComponentArrayAddMethod.value,
    });

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
    store.commit("pageBuilderState/setPushComponents", {
        component: clonedComponent,
        componentArrayAddMethod: getComponentArrayAddMethod.value,
    });

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
        page: page,
        search_query: search_query.value,
        category: categorySelected.value,
    });
};

const fetchComponents = function (page) {
    //remember old search value while paginating
    if (getFetchedComponents.value?.fetchedData?.oldInput?.search_query) {
        search_query.value =
            getFetchedComponents.value?.fetchedData?.oldInput?.search_query;
    }
    //
    // dispatch
    store.dispatch("pageBuilderState/loadComponents", {
        page: page,
        search_query: search_query.value,
        category: categorySelected.value,
    });
};

// get result for "laravel pagination" package
const getResultsForPage = (page = 1) => {
    fetchComponents(page);
};

const handleCategory = function (category) {
    categorySelected.value = category;

    // dispatch
    store.dispatch("pageBuilderState/loadComponents", {
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
        minHeight=""
        maxHeight=""
    >
        <div
            class="w-full relative inline-block align-bottom text-left overflow-hidden transform transition-all sm:align-middle"
        >
            <div class="flex items-center border-b border-gray-200 p-4 mb-2">
                <div class="flex-1">
                    <h3 class="tertiaryHeader my-0 py-0">
                        {{ title }}
                    </h3>
                </div>
                <div @click="firstButton" class="flex-end cursor-pointer">
                    <span class="material-symbols-outlined"> close </span>
                </div>
            </div>

            <div class="p-4">
                <!-- content start -->
                <template
                    v-if="
                        !getFetchedComponents.isLoading &&
                        getFetchedComponents.isError
                    "
                >
                    <p class="myPrimaryInputError">
                        {{ getFetchedComponents.error }}
                    </p>
                </template>

                <!--  -->

                <!-- Loading # start -->
                <template v-if="getFetchedComponents.isLoading">
                    <SmallUniversalSpinner
                        class="h-40"
                        width="w-6"
                        height="h-6"
                        border="border-4"
                    ></SmallUniversalSpinner>
                </template>

                <!-- Actual data # start -->
                <template
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
                                    <span class="material-symbols-outlined">
                                        search
                                    </span>
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
                    </form>
                    <!-- Search # End -->

                    <!-- Categories # Start -->
                    <div class="flex gap-2 flex-wrap">
                        <button
                            @click="handleCategory({ name: 'All', id: null })"
                            class="myPrimaryTag font-medium"
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
                            getFetchedComponents.fetchedData
                                .component_categories"
                            :key="category.id"
                        >
                            <button
                                @click="
                                    handleCategory({
                                        name: category.name,
                                        id: category.id,
                                    })
                                "
                                class="myPrimaryTag font-medium"
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
                                    categorySelected.name === category.name
                                "
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
                                class="overflow-scroll min-h-[25rem] max-h-[25rem] grid myPrimaryGap md:grid-cols-2 grid-cols-2 w-full myPrimaryGap px-2 p-4 border border-myPrimaryLightGrayColor rounded"
                            >
                                <div
                                    class="overflow-hidden whitespace-pre-line flex-1 h-auto rounded border lg:py-10 py-8 px-2"
                                    v-for="component in getFetchedComponents &&
                                    getFetchedComponents.fetchedData &&
                                    getFetchedComponents.fetchedData
                                        .components &&
                                    Array.isArray(
                                        getFetchedComponents.fetchedData
                                            .components.data
                                    ) &&
                                    getFetchedComponents.fetchedData.components
                                        .data"
                                    :key="component.id"
                                >
                                    <div class="relative">
                                        <template
                                            v-if="
                                                component &&
                                                component.cover_images
                                            "
                                        >
                                            <p
                                                @click="
                                                    handleDropComponent(
                                                        component
                                                    )
                                                "
                                                class="myPrimaryParagraph pt-2 pb-2 text-xs cursor-pointer"
                                            >
                                                {{ component.title }}
                                            </p>
                                            <ThumbnailSmallImageSlider
                                                :images="component.cover_images"
                                                imageSize="medium_path"
                                                imageHeight="max-h-72"
                                                imageWidth="w-full cursor-pointer object-contain bg-white"
                                                :roundedFull="false"
                                                :squareButtons="true"
                                                @firstButtonClick="
                                                    handleDropComponent(
                                                        component
                                                    )
                                                "
                                                :imageClickable="true"
                                            ></ThumbnailSmallImageSlider>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <aside
                            class="md:w-2/6 overflow-scroll min-h-[30rem] max-h-[30rem] w-full border rounded pt-4 pb-2"
                        >
                            <div
                                class="grid grid-cols-2 gap-2 w-full overflow-y-scroll"
                            >
                                <!-- Unique HTML Component # start -->
                                <div
                                    @click="
                                        handleAddHelperComponent(
                                            helperComponent
                                        )
                                    "
                                    v-for="helperComponent in componentHelpers"
                                    :key="helperComponent.title"
                                    class="myPrimaryTag py-4 px-2 rounded-full cursor-pointer my-0 mx-2"
                                >
                                    <div
                                        class="flex justify-center items-center gap-4 text-xs font-medium"
                                    >
                                        <button
                                            type="button"
                                            class="flex items-center justify-center gap-2 my-2 cursor-pointer"
                                        >
                                            {{ helperComponent.title }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>
                </template>
                <!-- Actual data # start -->
            </div>
        </div>
        <div
            v-if="!getFetchedComponents.isError"
            class="bg-slate-50 px-2 py-4 flex sm:justify-end justify-center"
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
