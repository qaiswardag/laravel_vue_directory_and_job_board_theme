<script setup>
import {
    onMounted,
    computed,
    onBeforeMount,
    ref,
    watch,
    watchEffect,
} from "vue";
import PageBuilder from "@/composables/PageBuilder";
import DesignerPreviewModal from "@/Components/Modals/DesignerPreviewModal.vue";
import Preview from "@/Pages/PageBuilder/Preview.vue";
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
import SearchComponents from "@/Components/Search/SearchComponents.vue";

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
const pageBuilder = new PageBuilder(store);

const getMenuRight = computed(() => {
    return store.getters["designer/getMenuRight"];
});

const previewCurrentDesign = function () {
    pageBuilder.previewCurrentDesign();
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

const showModalAddComponent = ref(false);
const titleModalAddComponent = ref("");
const firstButtonTextSearchComponents = ref("");
const firstModalButtonSearchComponentsFunction = ref(null);

const handleAddComponent = function () {
    titleModalAddComponent.value = "Add Component to your Store";
    firstButtonTextSearchComponents.value = "Close";
    showModalAddComponent.value = true;
    console.log("it ran");

    firstModalButtonSearchComponentsFunction.value = function () {
        console.log("also this ran, close modal here");
        // handle show modal for unique content
        showModalAddComponent.value = false;
    };

    // end modal
};

const getComponents = computed(() => {
    return store.getters["designer/getComponents"];
});
const getComponent = computed(() => {
    return store.getters["designer/getComponent"];
});
const getElement = computed(() => {
    return store.getters["designer/getElement"];
});
const getElementOuterHTML = computed(() => {
    if (getElement.value === null) return;
    return getElement.value.outerHTML ? getElement.value.outerHTML : null;
});
watch(getElementOuterHTML, (newComponent) => {
    pageBuilder.handleDesignerMethods();
});

const deselectCurrentComponent = function () {
    store.commit("designer/setComponent", null);
    store.commit("designer/setElement", null);
};

onMounted(async () => {
    store.commit("designer/setComponent", null);
    store.commit("designer/setElement", null);

    // Rerender `get components` when it is loaded from local storage
    pageBuilder.addClickAndHoverEvents();
    pageBuilder.handleDesignerMethods();
});
</script>

<template xmlns="http://www.w3.org/1999/html">
    <SearchComponents
        v-if="showModalAddComponent"
        :show="showModalAddComponent"
        :team="team"
        :firstButtonText="firstButtonTextSearchComponents"
        :title="titleModalAddComponent"
        @firstModalButtonSearchComponentsFunction="
            firstModalButtonSearchComponentsFunction
        "
    ></SearchComponents>
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
        <p class="my-12">er: {{ JSON.stringify(getComponent) }}</p>
        <div class="relative h-full flex ml-4">
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
                    <!-- Added Compoents to DOM # start -->
                    <div
                        id="pagebuilder"
                        v-for="component in getComponents"
                        :key="component"
                        class="p-1 bg-white grow"
                    >
                        <div
                            @mouseup="
                                store.commit('designer/setComponent', component)
                            "
                            class="relative group"
                        >
                            <div v-html="component.html_code"></div>
                        </div>
                    </div>
                    <!-- Added Compoents to DOM # end -->

                    <!-- Add Component # start -->
                    <div
                        class="rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 my-12 mx-8"
                    >
                        <FolderPlusIcon
                            @click="handleAddComponent"
                            class="mx-auto h-12 w-12 text-gray-400 cursor-pointer"
                        ></FolderPlusIcon>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">
                            Add Component
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Get started by adding a new component.
                        </p>
                        <div class="mt-6">
                            <button
                                @click="handleAddComponent"
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
                </div>
                <!-- Add Component # end -->
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
