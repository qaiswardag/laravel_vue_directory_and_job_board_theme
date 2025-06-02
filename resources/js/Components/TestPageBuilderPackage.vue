<script setup>
import { ref, onMounted } from "vue";
// Professional static imports - everything from one package!
import {
    PageBuilder,
    PageBuilderComposable,
    usePageBuilderStateStore,
    useMediaLibraryStore,
    createPinia,
} from "vue-website-page-builder";
// Import external package CSS
import "vue-website-page-builder/style.css";

// Component refs
const isSetupComplete = ref(false);
const pageBuilderStateStore = ref(null);
const mediaLibraryStore = ref(null);
const pageBuilderComposable = ref(null);

// Test data
const testUser = ref({
    id: 1,
    first_name: "Test",
    last_name: "User",
    email: "test@example.com",
});

const testResource = ref({
    id: 1,
    name: "My Blog Post",
});

onMounted(async () => {
    try {
        // Create separate Pinia instance for external package
        const externalPinia = createPinia();

        // Initialize stores with the external Pinia instance
        pageBuilderStateStore.value = usePageBuilderStateStore(externalPinia);
        mediaLibraryStore.value = useMediaLibraryStore(externalPinia);

        // Professional instantiation - like what we'd do in StoreForm.vue
        const myPageBuilder = new PageBuilderComposable(
            pageBuilderStateStore.value,
            mediaLibraryStore.value
        );

        pageBuilderComposable.value = myPageBuilder;

        // Mark setup as complete so PageBuilder can render
        isSetupComplete.value = true;

        console.log("✅ External page builder package setup complete!");
    } catch (error) {
        console.error(
            "❌ Failed to setup external page builder package:",
            error
        );
    }
});
</script>

<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-4">
            Testing External Page Builder Package
        </h1>

        <!-- Page Builder Component - Renders after setup -->
        <div class="border-2 border-gray-300 rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-4">
                External Page Builder Component
            </h3>

            <div v-if="!isSetupComplete" class="text-blue-600">
                Setting up external package...
            </div>

            <component :is="PageBuilder" v-else />
        </div>

        <!-- Test Store Integration -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Store Integration Test</h3>
            <div class="bg-gray-100 p-4 rounded">
                <p>
                    <strong>PageBuilder State Store:</strong>
                    {{ pageBuilderStateStore ? "✅ Loaded" : "❌ Failed" }}
                </p>
                <p>
                    <strong>Media Library Store:</strong>
                    {{ mediaLibraryStore ? "✅ Loaded" : "❌ Failed" }}
                </p>
                <p>
                    <strong>PageBuilder Composable:</strong>
                    {{ pageBuilderComposable ? "✅ Loaded" : "❌ Failed" }}
                </p>
            </div>
        </div>

        <!-- Components Count -->
        <div class="mt-6" v-if="pageBuilderStateStore">
            <h3 class="text-lg font-semibold mb-2">Components Count</h3>
            <p>
                Current components:
                {{ pageBuilderStateStore.getComponents.length }}
            </p>
        </div>
    </div>
</template>
