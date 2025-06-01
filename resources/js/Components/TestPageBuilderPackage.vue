<script setup>
import { ref, onMounted, nextTick, markRaw } from "vue";
// Import external package CSS
import "vue-website-page-builder/style.css";

// Use refs to hold the imported components
const PageBuilder = ref(null);
const packageLoaded = ref(false);
const openPageBuilder = ref(false);
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

const testTeam = ref({
    id: 1,
    name: "Test Team",
    address: "Test Address",
});

onMounted(async () => {
    try {
        // Wait for next tick to ensure Vue app is fully mounted
        await nextTick();

        // Dynamic import of the external package
        const packageImports = await import("vue-website-page-builder");

        // Use markRaw to prevent Vue from making the component reactive
        PageBuilder.value = markRaw(packageImports.PageBuilder);

        // Create Pinia instance from the external package (don't install globally)
        const { createPinia } = await import("pinia");
        const externalPinia = createPinia();

        // Initialize stores with the external Pinia instance directly
        pageBuilderStateStore.value =
            packageImports.usePageBuilderStateStore(externalPinia);
        mediaLibraryStore.value =
            packageImports.useMediaLibraryStore(externalPinia);

        // Initialize composable (like PageBuilderComposable in external package)
        pageBuilderComposable.value = new packageImports.PageBuilderComposable(
            pageBuilderStateStore.value,
            mediaLibraryStore.value
        );

        // Match StoreForm.vue pattern exactly:
        // StoreForm.vue: const pageBuilder = new PageBuilder(store);
        // External pkg: const pageBuilder = new PageBuilderComposable(stores);
        const pageBuilder = pageBuilderComposable.value; // Same reference for clarity

        // Remove the check - we now know the exports
        console.log(
            "✅ External PageBuilder class equivalent found: PageBuilderComposable"
        );

        packageLoaded.value = true;

        console.log("✅ External page builder package loaded successfully!");
        console.log("Available exports:", Object.keys(packageImports));
        console.log("PageBuilder Component:", PageBuilder.value);
        console.log("PageBuilder State Store:", pageBuilderStateStore.value);
        console.log("Media Library Store:", mediaLibraryStore.value);
        console.log(
            "PageBuilder Composable (class):",
            pageBuilderComposable.value
        );
        console.log(
            "PageBuilder instance (StoreForm.vue equivalent):",
            pageBuilder
        );
    } catch (error) {
        console.error(
            "❌ Failed to load external page builder package:",
            error
        );
        packageLoaded.value = false;
    }
});
</script>

<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-4">
            Testing External Page Builder Package
        </h1>

        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Package Status</h2>
            <p class="text-green-600" v-if="packageLoaded">
                ✅ External package loaded successfully!
            </p>
            <p class="text-red-600" v-else>
                ❌ External package failed to load
            </p>
        </div>

        <div class="mb-6">
            <button
                @click="openPageBuilder = !openPageBuilder"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
                {{ openPageBuilder ? "Close" : "Open" }} Page Builder
            </button>
        </div>

        <!-- Test Page Builder Component -->
        <div
            v-if="openPageBuilder"
            class="border-2 border-gray-300 rounded-lg p-4"
        >
            <h3 class="text-lg font-semibold mb-4">
                External Page Builder Component
            </h3>

            <component :is="PageBuilder" v-if="packageLoaded && PageBuilder" />

            <div v-else class="text-red-500">
                Failed to load PageBuilder component
            </div>
        </div>

        <!-- Test Store Integration -->
        <div class="mt-6" v-if="packageLoaded">
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

        <!-- Components List -->
        <div class="mt-6" v-if="pageBuilderStateStore">
            <h3 class="text-lg font-semibold mb-2">Components Count</h3>
            <p>
                Current components:
                {{ pageBuilderStateStore.getComponents.length }}
            </p>
        </div>
    </div>
</template>
