<script setup>
import { ref, computed, onMounted, nextTick, inject } from "vue";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";

// Import PageBuilder composable - now with auto-injection capability!
import { PageBuilderComposable } from "vue-website-page-builder";

const search_query = ref("");
const categorySelected = ref({ name: "Components", id: null });
const isLoading = ref(false);
const error = ref(null);
const components = ref([]);

// Initialize PageBuilder - it will automatically inject stores!
const pageBuilder = new PageBuilderComposable();

// We still need to inject pageBuilderStateStore for the computed property
const pageBuilderStateStore = inject("pageBuilderStateStore");

// Inject the modal close function
const closeAddComponentModal = inject("closeAddComponentModal");

const getComponentArrayAddMethod = computed(() => {
    return pageBuilderStateStore.getComponentArrayAddMethod;
});

const handlecategorySelected = function (category) {
    categorySelected.value = category;
};

const handleDropComponent = async function (componentObject) {
    if (!pageBuilder || !pageBuilderStateStore) {
        console.error("PageBuilder or store not available");
        return;
    }

    await nextTick();
    const clonedComponent = pageBuilder.cloneCompObjForDOMInsertion({
        html_code: componentObject.html_code,
        id: componentObject.id,
    });

    await nextTick();

    pageBuilderStateStore.setPushComponents({
        component: clonedComponent,
        componentArrayAddMethod: getComponentArrayAddMethod.value,
    });

    await nextTick();
    pageBuilder.setEventListenersForElements();

    console.log("Component added to page builder:", componentObject);

    // Auto-close the modal after successfully adding the component
    if (closeAddComponentModal) {
        closeAddComponentModal();
    }
};

const handleAddHelperComponent = async function (helperComponentObject) {
    if (!pageBuilder || !pageBuilderStateStore) {
        console.error("PageBuilder or store not available");
        return;
    }

    await nextTick();
    const clonedComponent = pageBuilder.cloneCompObjForDOMInsertion({
        html_code: helperComponentObject.html_code,
        id: helperComponentObject.id,
    });

    await nextTick();

    pageBuilderStateStore.setPushComponents({
        component: clonedComponent,
        componentArrayAddMethod: getComponentArrayAddMethod.value,
    });

    await nextTick();
    pageBuilder.setEventListenersForElements();

    console.log(
        "Helper component added to page builder:",
        helperComponentObject
    );

    // Auto-close the modal after successfully adding the helper component
    if (closeAddComponentModal) {
        closeAddComponentModal();
    }
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

        // Debug logging
        console.log("Raw JSON data:", data);
        console.log("Components object:", data.components);
        console.log("Components data array:", data.components?.data);
        console.log(
            "Number of components:",
            data.components?.data
                ? data.components.data.length
                : "No components.data property"
        );

        // Fix: Access the nested data array and add id fields
        const rawComponents = data.components?.data || [];
        components.value = rawComponents.map((component, index) => ({
            ...component,
            id: component.id || index + 1, // Add id if missing, using index + 1
            name: component.title || component.name || `Component ${index + 1}`, // Use title as name
        }));

        console.log("Final components.value:", components.value);
        console.log("Number of processed components:", components.value.length);
    } catch (err) {
        error.value = `Failed to fetch components: ${err.message}`;
        console.error("Error fetching components:", err);
    } finally {
        isLoading.value = false;
    }
};

// Simple HTML elements for the helper components
const componentHelpers = [
    {
        html_code: `
        <section>
        <div class="relative py-4">
        <div class="mx-auto max-w-7xl lg:px-4 px-2">
        <div>
        <p><strong>Text</strong></p><p>Tempus imperdiet nulla malesuada pellentesque elit eget gravida cum sociis. Erat nam at lectus urna duis convallis convallis. Congue mauris rhoncus aenean vel elit scelerisque. 
        Turpis tincidunt id aliquet risus feugiat in ante. Tincidunt dui ut ornare lectus sit. Ipsum dolor sit amet consectetur. Viverra ipsum nunc aliquet bibendum enim facilisis gravida neque convallis.<br><br>Dignissim sodales ut eu sem integer vitae justo eget magna. 
        Ac turpis egestas maecenas pharetra convallis. Mauris sit amet massa vitae. Ut tellus elementum sagittis vitae et. Sed risus ultricies tristique nulla aliquet enim tortor. Nunc mattis enim ut tellus elementum sagittis vitae et leo. Quis vel eros donec ac odio tempor. 
        Faucibus scelerisque eleifend donec pretium. <br><br>Adipiscing bibendum est ultricies integer quis auctor elit. Vestibulum morbi blandit cursus risus at ultrices mi tempus imperdiet. Id porta nibh venenatis cras sed felis eget. Gravida dictum fusce ut placerat orci nulla. 
        Consequat mauris nunc congue nisi vitae suscipit tellus mauris. <br><br></p><p><strong>List</strong></p><ul><li><p>Integer enim neque volutpat ac tincidunt vitae semper quis. Sit amet consectetur adipiscing elit pellentesque.</p></li><li><p>Urna cursus eget nunc scelerisque viverra. 
        Cursus metus aliquam eleifend mi in nulla posuere. Lobortis elementum nibh tellus molestie nunc non blandit massa.</p></li><li><p>Sodales ut eu sem integer vitae justo eget magna. Scelerisque felis imperdiet proin fermentum leo vel orci. Nunc id cursus metus aliquam eleifend.</p></li></ul>
        </div>
        </div>
        </div>
        </section>`,
        id: null,
        title: "Text",
        icon: `
        <span class="material-symbols-outlined">
        text_fields
        </span>
        `,
    },
    {
        html_code: `<section><div class="relative py-4"><div class="mx-auto max-w-7xl lg:px-4 px-2"><div class="break-words"><h2>Consequat mauris nunc congue</h2></div></div></div></section>`,
        id: null,
        title: "Header H2",
        icon: `
        <span class="material-symbols-outlined">
        format_h2
        </span>
        `,
    },
    {
        html_code: `<section><div class="relative py-4"><div class="mx-auto max-w-7xl lg:px-4 px-2"><div class="break-words"><h3>Consequat mauris nunc congue</h3></div></div></div></section>`,
        id: null,
        title: "Header H3",
        icon: `
        <span class="material-symbols-outlined">
        format_h3
        </span>
        `,
    },
    {
        html_code: `
        <section>
        <div class="py-4">
        <div class="mx-auto max-w-7xl lg:px-4 px-2">
        <div  id="youtube-video" class="w-full aspect-video bg-slate-100 border border-slate-200 rounded-xl lg:p-8 md:p-6 p-4">
     
        <iframe
        frameborder="0" 
        allowfullscreen
        class="w-full aspect-video bg-gray-600 border border-slate-800 rounded-xl"
        src="" 
        allow="accelerometer; autoplay; clipboard-write;" allowfullscreen>
        </iframe>
        </div>
        </div>
        </div>
        </section>`,
        id: null,
        title: "YouTube Video",
        icon: `
        <span class="material-symbols-outlined">
        play_circle
        </span>
        `,
    },
    {
        html_code: `<section><div class="relative py-8"><div class="absolute inset-0 flex items-center" aria-hidden="true"><div class="w-full border-4 border-gray-800 leading-none"></div></div><div class="relative flex justify-start"></div></div></section>`,
        id: null,
        title: "Break Divider",
        icon: `
        <span class="material-symbols-outlined">
        horizontal_rule
        </span>
        `,
    },
    {
        html_code: `<section>\n<div class=\"w-full md:pt-2 md:pb-2 pt-4 pb-4 lg:px-4 px-2\">\n<div class=\"mx-auto max-w-7xl\">\n<div id=\"linktree\"\nclass=\"border-2 border-gray-600 flex items-centre justify-start rounded-md font-medium text-black\">\n<p>\n<a target=\"_blank\" rel=\"noopener noreferrer nofollow\" href=\"https://www.google.com\">Link to landing page</a>\n</p>\n</div>\n</div>\n</div>\n</section>`,
        id: null,
        title: "Link",
        icon: `
            <span class="material-symbols-outlined">
            horizontal_rule
            </span>
            `,
    },
];
onMounted(async () => {
    await fetchComponents();
});
</script>

<template>
    <div class="w-full">
        <div class="flex items-center border-b border-gray-200 p-4 mb-2">
            <div class="flex-1">
                <h3 class="text-lg font-semibold my-0 py-0">
                    Search Custom Component
                </h3>
            </div>
        </div>

        <div class="p-4">
            <!-- Error message -->
            <template v-if="!isLoading && error">
                <p class="text-red-600 bg-red-50 p-3 rounded">
                    {{ error }}
                </p>
            </template>

            <!-- Loading spinner -->
            <template v-if="isLoading">
                <SmallUniversalSpinner
                    class="h-40"
                    width="w-6"
                    height="h-6"
                    border="border-4"
                />
            </template>

            <!-- Content -->
            <template v-if="!error && !isLoading">
                <div class="flex gap-2 flex-wrap mb-4">
                    <button
                        @click="
                            handlecategorySelected({
                                name: 'Components',
                                id: null,
                            })
                        "
                        class="myPrimaryTag font-medium"
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
                        @click="
                            handlecategorySelected({
                                name: 'HTML Elements',
                                id: null,
                            })
                        "
                        class="myPrimaryTag font-medium"
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

                <div class="h-full flex md:flex-row flex-col gap-4">
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
                                <div
                                    class="overflow-hidden whitespace-pre-line flex-1 h-auto rounded-lg border border-gray-200 lg:py-10 py-8 px-2 hover:shadow-md transition-shadow cursor-pointer"
                                    v-for="component in components"
                                    :key="component.id"
                                    @click="handleDropComponent(component)"
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
                                                alt="Component preview"
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
                                </div>

                                <!-- Show message if no components -->
                                <div
                                    v-if="components.length === 0"
                                    class="col-span-full text-center py-8 text-gray-500"
                                >
                                    No components found. Make sure
                                    components.json exists and contains
                                    component data.
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
                                                @click="
                                                    handleAddHelperComponent(
                                                        helperComponent
                                                    )
                                                "
                                                type="button"
                                                class="mySecondaryButton"
                                            >
                                                <span class="text-sm">+</span>
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
                                    @click="
                                        handleAddHelperComponent(
                                            helperComponent
                                        )
                                    "
                                    type="button"
                                    class="mySecondaryButton"
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
