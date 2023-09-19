<script setup>
import Draggable from "vuedraggable";
import {
    onMounted,
    computed,
    onBeforeMount,
    ref,
    watch,
    watchEffect,
} from "vue";
import Designer from "@/composables/Designer";
import DesignerPreviewModal from "@/Components/Modals/DesignerPreviewModal.vue";
import Preview from "@/Pages/Designer/Preview.vue";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import {
    Bars3Icon,
    XMarkIcon,
    EyeIcon,
    BellIcon,
    EyeSlashIcon,
    AdjustmentsVerticalIcon,
    Square3Stack3DIcon,
    Squares2X2Icon,
    XCircleIcon,
    BoltSlashIcon,
    FolderPlusIcon,
} from "@heroicons/vue/24/outline";
import { useStore } from "vuex";
import OptionsDropdown from "@/Components/Builder/DropdownsPlusToggles/OptionsDropdown.vue";
import RightSidebarEditor from "@/Components/Builder/EditorMenu/RightSidebarEditor.vue";
import Spinner from "@/Components/Builder/Loaders/Spinner.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";

const emit = defineEmits(["previewCurrentDesign"]);

const props = defineProps({
    team: {
        required: true,
    },
    user: {
        required: true,
    },
});

const store = useStore();
const designer = new Designer(store);

const showModalAddComponont = ref(false);

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

const getMenuLeft = computed(() => {
    return store.getters["designer/getMenuLeft"];
});
const getMenuRight = computed(() => {
    return store.getters["designer/getMenuRight"];
});
const getMenuPreview = computed(() => {
    return store.getters["designer/getMenuPreview"];
});

const categories = ref(null);

const previewCurrentDesign = function () {
    designer.previewCurrentDesign();
};

const openDesignerPreviewModal = ref(false);
const firstDesignerPreviewModalButtonFunction = ref(null);

const handleDesignerPreview = function () {
    previewCurrentDesign();
    // set modal standards
    openDesignerPreviewModal.value = true;
    // handle click
    firstDesignerPreviewModalButtonFunction.value = function () {
        // set open modal
        openDesignerPreviewModal.value = false;
    };
    // end modal
};

// clone
const cloneComponent = function (cloneComponent) {
    return designer.cloneComponent(cloneComponent);
};

// When HTML component is dropped into the DOM
const onDrop = function (droppedElement, targetIndex, originalEvent) {
    // designer.saveCurrentDesignWithTimer();
};
const getElement = computed(() => {
    return store.getters["designer/getElement"];
});
const getElementOuterHTML = computed(() => {
    if (getElement.value === null) return;
    return getElement.value.outerHTML ? getElement.value.outerHTML : null;
});
watch(getElementOuterHTML, (newComponent) => {
    designer.handleDesignerMethods();
});

const getComponents = computed(() => {
    return store.getters["designer/getComponents"];
});

const deselectCurrentComponent = function () {
    store.commit("designer/setComponent", null);
    store.commit("designer/setElement", null);
    designer.removeHoveredAndSelected();
};

const draggingItem = ref(false);

const onDragStart = function (evt) {
    draggingItem.value = true;
};
const onDragEnd = function () {
    draggingItem.value = false;
};
//
//
const awaitComponentsOnMounted = ref([]);
//
//

const getFetchedComponents = computed(() => {
    return store.getters["designer/getFetchedComponents"];
});

const activeLibrary = ref({ name: "Forms" });

// handleAddComponenModal # Start
// handleAddComponenModal # Start
const handleAddComponenModal = function () {
    // set modal standards
    showModalAddComponont.value = true;
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = "Add component";
    descriptionModal.value = "Add Component";
    firstButtonModal.value = "Close";
    thirdButtonModal.value = "Add Component";

    // handle click
    firstModalButtonFunction.value = function () {
        // set open modal
        showModalAddComponont.value = false;
    };
    //
    // handle click
    secondModalButtonFunction.value = function () {
        showModalAddComponont.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        showModalAddComponont.value = false;
    };
    // end modal
};
// handleAddComponenModal # End
// handleAddComponenModal # End

const handleComponent = function (component) {
    console.log("user cliked on component:", component);
};

onMounted(async () => {
    // fetch components
    awaitComponentsOnMounted.value = await store.dispatch(
        "designer/loadComponents",
        props.team
    );

    if (
        awaitComponentsOnMounted.value !== undefined &&
        awaitComponentsOnMounted.value !== null
    ) {
        categories.value = awaitComponentsOnMounted.value.component_categories;
    }

    if (
        awaitComponentsOnMounted.value !== undefined &&
        awaitComponentsOnMounted.value !== null
    ) {
        // Filter components based on the active category
        const activeCategoryComponents =
            awaitComponentsOnMounted.value.components.filter((component) => {
                return component;
            });

        awaitComponentsOnMounted.value = activeCategoryComponents;
    }
    //
    //
    //
    //
    //
    //
    //
    //
    //
    store.commit("designer/setComponent", null);
    store.commit("designer/setElement", null);

    // Rerender `get components` when it is loaded from local storage
    designer.addClickAndHoverEvents();
    designer.handleDesignerMethods();
});
</script>

<template xmlns="http://www.w3.org/1999/html">
    <DynamicModal
        v-if="showModalAddComponont"
        maxWidth="4xl"
        :show="showModalAddComponont"
        :type="typeModal"
        :gridColumnAmount="gridColumnModal"
        :title="titleModal"
        :description="descriptionModal"
        :firstButtonText="firstButtonModal"
        :secondButtonText="secondButtonModal"
        :thirdButtonText="thirdButtonModal"
        @firstModalButtonFunction="firstModalButtonFunction"
        @secondModalButtonFunction="secondModalButtonFunction"
        @thirdModalButtonFunction="thirdModalButtonFunction"
    >
        <header></header>
        <main class="h-full md:max-h-[30rem] max-h-[20rem] p-2">
            <div class="flex gap-2 flex-wrap">
                <button class="myPrimaryTag">All</button>
                <template
                    v-for="category in Array.isArray(categories) && categories"
                    :key="category.id"
                >
                    <button class="myPrimaryTag">
                        {{ category.name }}
                    </button>
                </template>
            </div>
            <main
                class="h-full flex md:flex-row flex-col myPrimaryGap mt-2 p-2"
            >
                <section
                    class="h-full md:max-h-[20rem] max-h-[20rem] overflow-y-scroll p-2 md:w-4/6"
                >
                    <div
                        class="grid myPrimaryGap md:grid-cols-2 grid-cols-2 divide-y divide-gray-200 w-full gap-2 px-2 p-4 border border-myPrimaryLightGrayColor rounded"
                    >
                        <div
                            class="px-4 py-4 bg-gray-50 rounded-md"
                            v-for="component in Array.isArray(
                                awaitComponentsOnMounted
                            ) && awaitComponentsOnMounted"
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
                            I sidste uge åbnede det amerikanske sportstøjsmærke
                            Gant dørene til deres nyeste konceptbutik og dermed
                            har Amagertorv i København fået endnu en
                            detail-destination til sine mange daglige besøgende.
                            Butikken, der spreder sig over 370 kvadratmeter,
                            skal favne brandets arv og historie, der især
                            associeres med den amerikanske østkysts Ivy
                        </p>
                        <p class="py-4 px-2">
                            I sidste uge åbnede det amerikanske sportstøjsmærke
                            Gant dørene til deres nyeste konceptbutik og dermed
                            har Amagertorv i København fået endnu en
                            detail-destination til sine mange daglige besøgende.
                            Butikken, der spreder sig over 370 kvadratmeter,
                            skal favne brandets arv og historie, der især
                            associeres med den amerikanske østkysts Ivy
                        </p>
                        <p class="py-4 px-2">
                            I sidste uge åbnede det amerikanske sportstøjsmærke
                            Gant dørene til deres nyeste konceptbutik og dermed
                            har Amagertorv i København fået endnu en
                            detail-destination til sine mange daglige besøgende.
                            Butikken, der spreder sig over 370 kvadratmeter,
                            skal favne brandets arv og historie, der især
                            associeres med den amerikanske østkysts Ivy
                        </p>
                        <p class="py-4 px-2">
                            I sidste uge åbnede det amerikanske sportstøjsmærke
                            Gant dørene til deres nyeste konceptbutik og dermed
                            har Amagertorv i København fået endnu en
                            detail-destination til sine mange daglige besøgende.
                            Butikken, der spreder sig over 370 kvadratmeter,
                            skal favne brandets arv og historie, der især
                            associeres med den amerikanske østkysts Ivy
                        </p>
                    </div>
                </aside>
            </main>
        </main>
    </DynamicModal>
    <DesignerPreviewModal
        :show="openDesignerPreviewModal"
        @firstDesignerPreviewModalButtonFunction="
            firstDesignerPreviewModalButtonFunction
        "
    >
        <Preview></Preview>
    </DesignerPreviewModal>

    <div
        class="w-full inset-x-0 h-[90vh] lg:pt-0 pt-0-z-10 bg-white overflow-x-scroll"
    >
        <div class="relative h-full flex">
            <aside
                aria-label="sidebar"
                :class="{
                    'w-0': !getMenuLeft,
                    'w-60': getMenuLeft,
                    'rounded-r-[0rem]': getMenuPreview,
                }"
                class="h-full flex-shrink-0 shadow-2xl rounded-r-2xl overflow-hidden mr-4 duration-150"
                @mouseleave="store.commit('designer/setMenuPreview', false)"
            >
                <div class="sticky h-full w-60 overflow-hidden">
                    <nav
                        aria-label="Sidebar"
                        class="h-full bg-white pt-2.5 pr-0 pb-4 pl-4"
                    >
                        <div
                            class="flex flex-row justify-end border-b pb-3 mb-3 pr-4"
                        >
                            <button
                                type="button"
                                @click="
                                    store.commit(
                                        'designer/setMenuLeft',
                                        false
                                    ) &&
                                        store.commit(
                                            'designer/setMenuPreview',
                                            false
                                        )
                                "
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                            >
                                <XMarkIcon
                                    class="shrink-0 h-4 w-4 m-2 stroke-2"
                                >
                                </XMarkIcon>
                            </button>
                        </div>

                        <p class="myPrimaryParagraph font-medium pt-4 pr-4">
                            COMPONENTS
                        </p>
                        <ul
                            @mouseover.self="
                                store.commit('designer/setMenuPreview', false)
                            "
                            class="flex flex-col pt-4 pr-0 font-normal h-full overflow-y-auto pb-32"
                        >
                            <li
                                v-for="category in categories"
                                :key="category"
                                :class="{
                                    'bg-gray-100 text-gray-900':
                                        activeLibrary.name === category.name &&
                                        getMenuPreview === true,
                                }"
                                class="w-full myPrimaryParagrap font-medium py-4 pl-2 pr-0 capitalize cursor-pointer rounded-l-lg"
                                @mouseover="
                                    activeLibrary = category;
                                    store.commit(
                                        'designer/setMenuPreview',
                                        true
                                    );
                                "
                            >
                                {{ category.name }}
                            </li>
                        </ul>
                    </nav>
                </div>

                <!--Preview - start-->
                <aside
                    aria-label="saidebar"
                    :class="[!getMenuPreview ? '-left-[30rem]' : 'left-56']"
                    class="absolute z-10 w-[20rem] h-full duration-200 top-0 rounded-r-2xl shadow-2xl bg-white"
                >
                    <div class="flex flex-col gap-4 p-4 h-full font-normal">
                        <draggable
                            :clone="cloneComponent"
                            :group="{
                                name: 'components',
                                pull: 'clone',
                                put: false,
                            }"
                            :list="awaitComponentsOnMounted"
                            :sort="false"
                            class="flex flex-col gap-4 pr-4 overflow-y-auto"
                            item-key="id"
                            @start="onDragStart"
                            @end="onDragEnd"
                        >
                            <template #item="{ element }">
                                <div v-if="element">
                                    <div
                                        class="myPrimaryComponentToDrag"
                                        v-if="
                                            Array.isArray(
                                                element.cover_images
                                            ) &&
                                            element.cover_images.length !== 0
                                        "
                                    >
                                        <ThumbnailSmallImageSlider
                                            :images="element.cover_images"
                                            imageSize="medium_path"
                                            imageHeight="md:h-60 h-40"
                                            imageWidth="md:w-60 w-40"
                                            :roundedFull="false"
                                        ></ThumbnailSmallImageSlider>
                                    </div>
                                </div>
                            </template>
                        </draggable>
                    </div>
                </aside>
            </aside>
            <!--Preview - end-->

            <!-- Bars - start -->
            <div
                v-show="getMenuLeft === false"
                class="pt-2 mr-4 h-full flex-shrink-0 overflow-hidden"
            >
                <button
                    type="button"
                    @click="store.commit('designer/setMenuLeft', true)"
                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                >
                    <Square3Stack3DIcon class="shrink-0 h-4 w-4 m-2 stroke-2">
                    </Square3Stack3DIcon>
                </button>
            </div>
            <!-- Bars - end -->

            <main
                class="flex flex-col h-full grow rounded-2xl duration-300 shadow-2xl"
            >
                <div
                    class="flex items-center justify-between primary-gap rounded-t-2xl bg-myPrimaryLightGrayColor py-2 px-4"
                >
                    <div>
                        <div class="flex gap-2">
                            <span
                                class="w-2 h-2 rounded-full bg-red-400"
                            ></span>
                            <span
                                class="w-2 h-2 rounded-full bg-yellow-400"
                            ></span>
                            <span
                                class="w-2 h-2 rounded-full bg-green-400"
                            ></span>
                        </div>
                    </div>

                    <OptionsDropdown
                        @previewCurrentDesign="previewCurrentDesign"
                    ></OptionsDropdown>

                    <div class="flex items-center justify-center gap-2">
                        <button
                            type="button"
                            @click="handleDesignerPreview"
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                        >
                            <EyeIcon
                                class="shrink-0 h-4 w-4 m-2 stroke-2"
                            ></EyeIcon>
                        </button>
                        <button
                            type="button"
                            v-if="getElement !== null"
                            @click="deselectCurrentComponent"
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                        >
                            <BoltSlashIcon
                                class="shrink-0 h-4 w-4 m-2 stroke-2"
                            ></BoltSlashIcon>
                        </button>
                        <button
                            type="button"
                            v-if="getMenuRight === false"
                            @click="store.commit('designer/setMenuRight', true)"
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                        >
                            <Squares2X2Icon
                                class="shrink-0 h-4 w-4 m-2 stroke-2"
                            ></Squares2X2Icon>
                        </button>
                    </div>
                </div>

                <div class="overflow-y-auto">
                    <Draggable
                        id="pagebuilder"
                        :list="getComponents"
                        animation="200"
                        class="bg-white grow"
                        drag-class="opacity-0"
                        group="components"
                        handle=".cursor-grab"
                        item-key="id"
                        :onDrop="onDrop"
                        @change="designer.addClickAndHoverEvents"
                    >
                        <template #item="{ element }">
                            <div
                                @mouseup="
                                    store.commit(
                                        'designer/setComponent',
                                        element
                                    )
                                "
                                class="relative group"
                            >
                                <div
                                    v-html="element.html_code"
                                    class="p-1"
                                ></div>
                            </div>
                        </template>
                    </Draggable>

                    <!-- Add Component # start -->
                    <div
                        class="rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 my-12 mx-8"
                    >
                        <FolderPlusIcon
                            class="mx-auto h-12 w-12 text-gray-400"
                        ></FolderPlusIcon>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">
                            Add Component
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Get started adding a new component.
                        </p>
                        <div class="mt-6">
                            <button
                                @click="handleAddComponenModal"
                                type="button"
                                class="myPrimaryButton"
                            >
                                <FolderPlusIcon
                                    class="-ml-0.5 mr-1.5 h-5 w-5"
                                ></FolderPlusIcon>
                                New Component
                            </button>
                        </div>
                    </div>
                    <!-- Add Component # end -->
                </div>
            </main>

            <aside
                aria-label="Menu"
                :class="{ 'w-0': !getMenuRight, 'w-80 ml-4': getMenuRight }"
                class="h-full duration-300 z-20 flex-shrink-0 overflow-hidden shadow-2xl rounded-l-2xl bg-white"
            >
                <RightSidebarEditor
                    :user="user"
                    :team="team"
                    @closeEditor="store.commit('designer/setMenuRight', false)"
                >
                </RightSidebarEditor>
            </aside>
        </div>
        <Spinner
            v-if="
                getFetchedComponents &&
                getFetchedComponents.isLoading === true &&
                getFetchedComponents.isError === false
            "
        >
        </Spinner>
    </div>
</template>

<style>
#pagebuilder a {
    cursor: default;
}
#pagebuilder [selected] {
    outline: rgb(185, 16, 16) dashed 4px !important;
    outline-offset: -2px !important;
}
#pagebuilder [hovered] {
    outline: rgb(0, 140, 14, 1) dashed 4px !important;
    outline-offset: -2px !important;
}

.sortable-ghost {
    display: flex;
    justify-content: center;
}

.sortable-ghost > * {
    width: 100%;
}
</style>
