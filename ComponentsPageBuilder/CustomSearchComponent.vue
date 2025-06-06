<script setup>
import { ref, onMounted, inject } from "vue";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";

// Import PageBuilder and modal control - professional way!
import {
    PageBuilderClass,
    usePageBuilderModal,
} from "vue-website-page-builder";

const search_query = ref("");
const categorySelected = ref({ name: "Components", id: null });
const isLoading = ref(false);
const error = ref(null);
const components = ref([]);

const pageBuilderStateStore = inject("pageBuilderStateStore");
const mediaLibraryStore = inject("mediaLibraryStore");

// Initialize PageBuilder with explicit store passing - professional way!
const pageBuilderClass = new PageBuilderClass(
    pageBuilderStateStore,
    mediaLibraryStore
);

const componentHelpers = () => {
    return [
        {
            html_code: `<section>...long HTML...</section>`,
            id: null,
            title: "Text",
        },
        {
            html_code: `<section><div class="relative py-4">...</div></section>`,
            id: null,
            title: "Header H2",
        },
    ];
};

// Get modal control functions - professional import pattern!
const { closeAddComponentModal } = usePageBuilderModal();

const handlecategorySelected = function (category) {
    categorySelected.value = category;
};

// Super simple component addition with professional modal closing!
const handleDropComponent = function (componentObject) {
    pageBuilderClass.addComponent(componentObject);
    closeAddComponentModal();
};

// Super simple helper component addition with professional modal closing!
const handleAddHelperComponent = function (helperComponentObject) {
    pageBuilderClass.addComponent(helperComponentObject);
    closeAddComponentModal();
};

const fetchComponents = async function () {
    isLoading.value = true;
    error.value = null;

    try {
        const response = await fetch("/components.json");
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();

        // Process components from JSON
        const rawComponents = data.components?.data || [];
        components.value = rawComponents.map((component, index) => ({
            ...component,
            id: component.id || index + 1,
            name: component.title || component.name || `Component ${index + 1}`,
        }));
    } catch (err) {
        error.value = `Failed to fetch components: ${err.message}`;
        console.error("Error fetching components:", err);
    } finally {
        isLoading.value = false;
    }
};

onMounted(async () => {
    await fetchComponents();
});
</script>

<template>
    <div class="w-full relative">
        <p class="myPrimaryParagraph">Custom Search Component</p>
        <div class="p-4">
            <!-- Error message -->
            <template v-if="!isLoading && error">
                <p class="text-red-600 bg-red-50 p-3 rounded">
                    {{ error }}
                </p>
                <!-- Always provide a focusable element during error -->
                <button
                    type="button"
                    @click="fetchComponents"
                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Try Again
                </button>
            </template>

            <!-- Loading spinner -->
            <template v-if="isLoading">
                <SmallUniversalSpinner
                    class="h-40"
                    width="w-6"
                    height="h-6"
                    border="border-4"
                />
                <!-- Always provide a focusable element during loading -->
                <button
                    type="button"
                    @click="closeAddComponentModal"
                    class="mt-4 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                >
                    Cancel
                </button>
            </template>

            <!-- Content -->
            <template v-if="!error && !isLoading">
                <div class="flex gap-2 flex-wrap mb-4">
                    <button
                        type="button"
                        @click="
                            handlecategorySelected({
                                name: 'Components',
                                id: null,
                            })
                        "
                        class="myPrimaryTag font-medium focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="[
                            {
                                'bg-myPrimaryLinkColor text-white':
                                    categorySelected.name === 'Components',
                            },
                        ]"
                    >
                        Components
                    </button>
                    <button
                        type="button"
                        @click="
                            handlecategorySelected({
                                name: 'HTML Elements',
                                id: null,
                            })
                        "
                        class="myPrimaryTag font-medium focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="[
                            {
                                'bg-myPrimaryLinkColor text-white':
                                    categorySelected.name === 'HTML Elements',
                            },
                        ]"
                    >
                        HTML Elements
                    </button>
                </div>

                <div class="h-full flex md:flex-row gap-4">
                    <section class="md:w-4/6">
                        <!-- Components Tab -->
                        <template
                            v-if="
                                categorySelected &&
                                categorySelected.name === 'Components'
                            "
                        >
                            <div
                                class="overflow-auto min-h-[25rem] max-h-[25rem] grid gap-4 md:grid-cols-2 grid-cols-2 w-full p-4 border border-gray-300 rounded-lg"
                            >
                                <button
                                    type="button"
                                    class="overflow-hidden whitespace-pre-line flex-1 h-auto rounded-lg border border-gray-200 lg:py-10 py-8 px-2 hover:shadow-md transition-shadow cursor-pointer relative z-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    v-for="component in components"
                                    :key="component.id"
                                    @click="handleDropComponent(component)"
                                    :aria-label="`Add component ${
                                        component.name || 'Component'
                                    }`"
                                >
                                    <div class="relative">
                                        <template
                                            v-if="
                                                component &&
                                                component.cover_image
                                            "
                                        >
                                            <img
                                                :src="component.cover_image"
                                                :alt="`Preview of ${
                                                    component.name ||
                                                    'Component'
                                                }`"
                                                class="max-h-72 object-contain bg-white mx-auto"
                                            />
                                        </template>
                                        <template v-else>
                                            <div
                                                class="max-h-72 bg-gray-100 flex items-center justify-center mx-auto p-8"
                                            >
                                                <span class="text-gray-500">{{
                                                    component.name ||
                                                    "Component"
                                                }}</span>
                                            </div>
                                        </template>
                                    </div>
                                </button>

                                <!-- Show message if no components -->
                                <div
                                    v-if="components.length === 0"
                                    class="col-span-full text-center py-8 text-gray-500"
                                >
                                    <p>
                                        No components found. Make sure
                                        components.json exists and contains
                                        component data.
                                    </p>
                                    <!-- Provide a focusable element when no components -->
                                    <button
                                        type="button"
                                        @click="fetchComponents"
                                        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        Refresh Components
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- HTML Elements Tab -->
                        <template
                            v-if="
                                categorySelected &&
                                categorySelected.name === 'HTML Elements'
                            "
                        >
                            <div
                                class="overflow-auto min-h-[25rem] max-h-[25rem] p-4 border border-gray-300 rounded-lg"
                            >
                                <div class="flex gap-4 flex-wrap w-full">
                                    <div
                                        v-for="helperComponent in componentHelpers"
                                        :key="helperComponent.title"
                                    >
                                        <div
                                            class="flex justify-left items-center gap-4 text-xs font-medium"
                                        >
                                            <button
                                                type="button"
                                                @click="
                                                    handleAddHelperComponent(
                                                        helperComponent
                                                    )
                                                "
                                                class="flex items-center gap-2 px-3 py-2 text-xs bg-gray-100 hover:bg-gray-200 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                :aria-label="`Add ${helperComponent.title} element`"
                                            >
                                                <span>+</span>
                                                <span>{{
                                                    helperComponent.title
                                                }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </section>

                    <!-- Sidebar with HTML Elements -->
                    <aside
                        class="md:w-2/6 overflow-auto min-h-[30rem] max-h-[30rem] w-full border rounded-lg py-4 px-2"
                    >
                        <h4 class="font-semibold mb-3 text-gray-700">
                            Quick Add HTML Elements
                        </h4>
                        <div class="flex gap-2 flex-wrap w-full">
                            <div
                                v-for="helperComponent in componentHelpers"
                                :key="helperComponent.title + '_sidebar'"
                            >
                                <button
                                    type="button"
                                    @click="
                                        handleAddHelperComponent(
                                            helperComponent
                                        )
                                    "
                                    class="mySecondaryButton focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :aria-label="`Quick add ${helperComponent.title} element`"
                                >
                                    <span>+</span>
                                    <span>{{ helperComponent.title }}</span>
                                </button>
                            </div>
                        </div>
                    </aside>
                </div>
            </template>
        </div>
    </div>
</template>
