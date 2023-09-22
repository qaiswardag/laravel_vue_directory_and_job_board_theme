<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import { ref, computed, onMounted, onBeforeMount } from "vue";
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

const store = useStore();
const pageBuilder = new PageBuilder(store);

const awaitComponentsOnMounted = ref([]);

// use dynamic model
const getComponents = computed(() => {
    return store.getters["pageBuilderState/getComponents"];
});
const getFetchedComponents = computed(() => {
    return store.getters["pageBuilderState/getFetchedComponents"];
});

// const handleAddComponent = function (componentObject) {
//     const clonedComponentObject =
//         pageBuilder.cloneCompObjForDOMInsertion(componentObject);

//     console.log("er:", componentObject);

//     // pageBuilder.observePlusSyncHTMLElements();

//     pageBuilder.addClickAndHoverEvents();

//     store.commit("pageBuilderState/setPushComponents", clonedComponentObject);

//     firstButton();
// };

const handleAddComponent = function (component) {
    const clonedComponent = pageBuilder.cloneCompObjForDOMInsertion({
        html_code: component.html_code,
        id: component.id,
    });

    store.commit("pageBuilderState/setPushComponents", clonedComponent);

    firstButton();
};

const handleAddComponentHelper = function (componentObject) {
    firstButton();
};

onMounted(async () => {
    awaitComponentsOnMounted.value = await store.dispatch(
        "pageBuilderState/loadComponents",
        props.team
    );

    //
    //
    //
    //
    //
    //
    //
    //
    //
    // store.commit("pageBuilderState/setComponent", null);
    // store.commit("pageBuilderState/setElement", null);
    // Rerender `get components` when it is loaded from local storage
    // pageBuilder.addClickAndHoverEvents();
    // pageBuilder.handlePageBuilderMethods();
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
                <div class="flex gap-2 flex-wrap">
                    <button class="myPrimaryTag">All</button>
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
                        <button class="myPrimaryTag">
                            {{ category.name }}
                        </button>
                    </template>
                </div>

                <div
                    class="h-full flex md:flex-row flex-col myPrimaryGap mt-2 p-2 overflow-y-scroll"
                >
                    <section class="md:w-6/8">
                        <div
                            class="overflow-scroll min-h-[30rem] max-h-[30rem] grid myPrimaryGap md:grid-cols-2 grid-cols-2 w-full gap-2 px-2 p-4 border border-myPrimaryLightGrayColor"
                        >
                            <div
                                class="px-4 py-4 bg-gray-50 h-96"
                                v-for="component in getFetchedComponents &&
                                getFetchedComponents.fetchedData &&
                                Array.isArray(
                                    getFetchedComponents.fetchedData.components
                                ) &&
                                getFetchedComponents.fetchedData.components"
                                :key="component.id"
                            >
                                <div>
                                    <div>
                                        <p
                                            class="inline-block mb-4"
                                            :class="[
                                                {
                                                    'myPrimaryTag bg-myPrimaryLinkColor text-white':
                                                        component.published,
                                                },
                                                {
                                                    'myPrimaryTag bg-myPrimaryErrorColor text-white':
                                                        !component.published,
                                                },
                                            ]"
                                        >
                                            {{
                                                component.published
                                                    ? "Published"
                                                    : "Unpublished"
                                            }}
                                        </p>
                                    </div>

                                    <p class="myPrimaryParagraph text-xs">
                                        {{ component.title }}
                                    </p>

                                    <div
                                        @click="handleAddComponent(component)"
                                        class="border border-gray-200 p-2 mt-8 cursor-pointer"
                                    >
                                        <img
                                            v-if="
                                                component.cover_images.length <
                                                1
                                            "
                                            alt="image"
                                            class="w-full"
                                            src="/app-images/builder/components/default_component_image.jpg"
                                        />
                                        <ThumbnailSmallImageSlider
                                            :images="component.cover_images"
                                            imageSize="medium_path"
                                            imageHeight="md:h-40 h-40"
                                            imageWidth="w-full"
                                            :roundedFull="false"
                                        ></ThumbnailSmallImageSlider>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <aside
                        class="md:w-2/8 overflow-scroll min-h-[30rem] max-h-[30rem] w-full"
                    >
                        <div
                            class="p-2 flex flex-col w-full overflow-y-scroll border border-myPrimaryLightGrayColor divide-y divide-gray-200"
                        >
                            <!-- Unique HTML Component # start -->
                            <div
                                v-for="helperComponent in componentHelpers"
                                :key="helperComponent.title"
                                class="flex justify-between items-center gap-4 text-xs font-medium py-4"
                            >
                                <button
                                    @click="
                                        handleAddComponentHelper(
                                            helperComponent
                                        )
                                    "
                                    type="button"
                                    class="flex items-left gap-2 my-2 cursor-pointer"
                                >
                                    {{ helperComponent.title }}
                                </button>
                                <button
                                    @click="
                                        handleAddComponentHelper(
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
