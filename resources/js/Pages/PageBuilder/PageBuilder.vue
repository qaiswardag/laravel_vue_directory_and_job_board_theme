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
import PageBuilderPreviewModal from "@/Components/Modals/PageBuilderPreviewModal.vue";
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
import OptionsDropdown from "@/Components/PageBuilder/DropdownsPlusToggles/OptionsDropdown.vue";
import RightSidebarEditor from "@/Components/PageBuilder/EditorMenu/RightSidebarEditor.vue";
import Spinner from "@/Components/PageBuilder/Loaders/Spinner.vue";
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
    return store.getters["pageBuilderState/getMenuRight"];
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
    titleModalAddComponent.value = "Add Page Components to Store";
    firstButtonTextSearchComponents.value = "Close";
    showModalAddComponent.value = true;

    firstModalButtonSearchComponentsFunction.value = function () {
        // handle show modal for unique content
        showModalAddComponent.value = false;
    };

    // end modal
};

const getComponents = computed(() => {
    return store.getters["pageBuilderState/getComponents"];
});
const getComponent = computed(() => {
    return store.getters["pageBuilderState/getComponent"];
});
const getElement = computed(() => {
    return store.getters["pageBuilderState/getElement"];
});
const getElementAttributes = computed(() => {
    const element = getElement.value;

    if (element === null) return {};

    // Extract the attributes you want to watch
    const attributesToWatch = {
        src: element.getAttribute("src"),
        href: element.getAttribute("href"),
        style: element.getAttribute("style"),
        class: element.getAttribute("class"),
    };

    return attributesToWatch;
});

watch(getElementAttributes, (newAttributes, oldAttributes) => {
    // Check if any of the specified attributes have changed
    if (
        newAttributes.src !== oldAttributes.src ||
        newAttributes.href !== oldAttributes.href ||
        newAttributes.style !== oldAttributes.style ||
        newAttributes.class !== oldAttributes.class
    ) {
        // Trigger your method when any of the specified attributes change
        pageBuilder.handlePageBuilderMethods();
        pageBuilder.setEventListenersForElements();
    }
});

const handleSelectComponent = function (componentObject) {
    store.commit("pageBuilderState/setComponent", componentObject);
};

onMounted(async () => {
    pageBuilder.setEventListenersForElements();
});
</script>

<template>
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
    <PageBuilderPreviewModal
        :show="openDesignerPreviewModal"
        @firstDesignerPreviewModalButtonFunction="
            firstDesignerPreviewModalButtonFunction
        "
    >
        <Preview></Preview>
    </PageBuilderPreviewModal>

    <div
        class="w-full inset-x-0 h-[90vh] lg:pt-0 pt-0-z-10 bg-white overflow-x-scroll"
    >
        <div class="relative h-full flex ml-4">
            <main
                class="flex flex-col h-full grow rounded-2xl duration-300 shadow-2xl"
            >
                <div
                    @click="store.commit('pageBuilderState/setComponent', null)"
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
                            v-if="getMenuRight === false"
                            @click="
                                store.commit(
                                    'pageBuilderState/setMenuRight',
                                    true
                                )
                            "
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                        >
                            <Squares2X2Icon
                                class="shrink-0 h-4 w-4 m-2 stroke-2"
                            ></Squares2X2Icon>
                        </button>
                    </div>
                </div>

                <div
                    @click="store.commit('pageBuilderState/setComponent', null)"
                    class="p-2 bg-red-50 overflow-y-auto h-screen"
                >
                    <div id="pagebuilder" class="">
                        <!-- Added Compoents to DOM # start -->
                        <div
                            v-for="component in Array.isArray(getComponents) &&
                            getComponents"
                            :key="component"
                            class="p-1 bg-white grow"
                        >
                            <div
                                @mouseup="handleSelectComponent(component)"
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
                            <h3
                                class="mt-2 text-sm font-semibold text-gray-900"
                            >
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
                    @closeEditor="
                        store.commit('pageBuilderState/setMenuRight', false)
                    "
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

#pagebuilder [hovered] {
    outline: rgb(0, 140, 14, 1) solid 2px !important;
    outline-offset: -2px !important;
}

#pagebuilder [selected] {
    position: relative;

    outline: rgb(185, 16, 16) solid 2px !important;
    outline-offset: -2px !important;
}

/* #pagebuilder [selected]::before,
#pagebuilder [selected]::after {
    content: "";
    position: absolute;
    width: 20px;
    height: 20px;
    background-color: rgb(185, 16, 16);
    border-radius: 50%;
    z-index: 1000;
}

#pagebuilder [selected]::before {
    top: -10px;
    left: -10px;
}

#pagebuilder [selected]::after {
    top: -10px;
    right: -10px;
}

#pagebuilder [selected]::nth-child(2)::before {
    bottom: -10px;
    left: -10px;
}

#pagebuilder [selected]::nth-child(2)::after {
    bottom: -10px;
    right: -10px;
} */

/* sortable */

.sortable-ghost {
    display: flex;
    justify-content: center;
}

.sortable-ghost > * {
    width: 100%;
}
</style>
