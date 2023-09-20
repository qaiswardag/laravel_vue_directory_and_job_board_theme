<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import { ref, computed, onMounted, onBeforeMount } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";
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
import Designer from "@/composables/Designer";
import Spinner from "@/Components/Builder/Loaders/Spinner.vue";

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

const store = useStore();
const designer = new Designer(store);

const showModalAddComponont = ref(false);

const awaitComponentsOnMounted = ref([]);
const categories = ref(null);

// use dynamic model
const typeModal = ref("");
const gridColumnModal = ref(Number(1));
const titleModal = ref("");
const descriptionModal = ref("");
const firstButtonModal = ref("");
const secondButtonModal = ref(null);
const thirdButtonModal = ref(null);
// set dynamic modal handle functions
const firstModalButtonFunction = ref(null);
const secondModalButtonFunction = ref(null);
const thirdModalButtonFunction = ref(null);

const getFetchedComponents = computed(() => {
    return store.getters["designer/getFetchedComponents"];
});

const handleComponent = function (component) {
    console.log("user cliked on component:", component);
};

onMounted(async () => {
    awaitComponentsOnMounted.value = await store.dispatch(
        "designer/loadComponents",
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
    // store.commit("designer/setComponent", null);
    // store.commit("designer/setElement", null);
    // Rerender `get components` when it is loaded from local storage
    // designer.addClickAndHoverEvents();
    // designer.handleDesignerMethods();
});
</script>

<template>
    <Modal
        :show="show"
        @close="firstButton"
        maxWidth="5xl"
        minHeight="min-h-[50rem]"
        maxHeight="max-h-[50rem]"
    >
        <div
            class="px-4 w-full relative inline-block align-bottom text-left overflow-hidden transform transition-all sm:align-middle"
        >
            <Spinner
                class="flex justify-center items-center"
                v-if="
                    getFetchedComponents.isLoading &&
                    !getFetchedComponents.fetchedData &&
                    !getFetchedComponents.isError
                "
            ></Spinner>

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
                v-if="!getFetchedComponents.isError"
                class="h-full md:max-h-[30rem] max-h-[20rem] p-2"
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
                <section
                    class="h-full flex md:flex-row flex-col myPrimaryGap mt-2 p-2"
                >
                    <section
                        class="h-full md:max-h-[20rem] max-h-[20rem] overflow-y-scroll md:w-4/6"
                    >
                        <div
                            class="grid myPrimaryGap md:grid-cols-2 grid-cols-2 divide-y divide-gray-200 w-full gap-2 px-2 p-4 border border-myPrimaryLightGrayColor rounded"
                        >
                            <div
                                class="px-4 py-4 bg-gray-50 rounded-md"
                                v-for="component in getFetchedComponents &&
                                getFetchedComponents.fetchedData &&
                                Array.isArray(
                                    getFetchedComponents.fetchedData.components
                                ) &&
                                getFetchedComponents.fetchedData.components"
                                :key="component.id"
                            >
                                <button
                                    @click="handleComponent(component)"
                                    type="button"
                                >
                                    {{ component.title }}
                                    <ThumbnailSmallImageSlider
                                        :images="component.cover_images"
                                        imageSize="medium_path"
                                        imageHeight="md:h-40 h-40"
                                        imageWidth="w-full"
                                        :roundedFull="false"
                                    ></ThumbnailSmallImageSlider>
                                </button>
                            </div>
                        </div>
                    </section>
                    <aside
                        aria-label="sidebar"
                        class="h-full md:max-h-[20rem] max-h-[20rem] md:w-2/6 w-full pl-2 border border-gray-200 overflow-y-scroll rounded"
                    >
                        <div
                            class="flex flex-col w-full divide-y divide-gray-200 p-2"
                        >
                            <p class="py-4 px-2">
                                I sidste uge åbnede det amerikanske
                                sportstøjsmærke Gant dørene til deres nyeste
                                konceptbutik og dermed har Amagertorv i
                                København fået endnu en detail-destination til
                                sine mange daglige besøgende. Butikken, der
                                spreder sig over 370 kvadratmeter, skal favne
                                brandets arv og historie, der især associeres
                                med den amerikanske østkysts Ivy
                            </p>
                            <p class="py-4 px-2">
                                I sidste uge åbnede det amerikanske
                                sportstøjsmærke Gant dørene til deres nyeste
                                konceptbutik og dermed har Amagertorv i
                                København fået endnu en detail-destination til
                                sine mange daglige besøgende. Butikken, der
                                spreder sig over 370 kvadratmeter, skal favne
                                brandets arv og historie, der især associeres
                                med den amerikanske østkysts Ivy
                            </p>
                            <p class="py-4 px-2">
                                I sidste uge åbnede det amerikanske
                                sportstøjsmærke Gant dørene til deres nyeste
                                konceptbutik og dermed har Amagertorv i
                                København fået endnu en detail-destination til
                                sine mange daglige besøgende. Butikken, der
                                spreder sig over 370 kvadratmeter, skal favne
                                brandets arv og historie, der især associeres
                                med den amerikanske østkysts Ivy
                            </p>
                            <p class="py-4 px-2">
                                I sidste uge åbnede det amerikanske
                                sportstøjsmærke Gant dørene til deres nyeste
                                konceptbutik og dermed har Amagertorv i
                                København fået endnu en detail-destination til
                                sine mange daglige besøgende. Butikken, der
                                spreder sig over 370 kvadratmeter, skal favne
                                brandets arv og historie, der især associeres
                                med den amerikanske østkysts Ivy
                            </p>
                        </div>
                    </aside>
                </section>
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
