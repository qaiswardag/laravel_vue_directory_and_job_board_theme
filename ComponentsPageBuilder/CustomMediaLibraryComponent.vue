<script setup>
import { ref, onMounted, computed } from "vue";

// Import PageBuilder and modal control - professional way!
import {
    PageBuilderComposable,
    usePageBuilderModal,
} from "vue-website-page-builder";

const loading = ref(false);
const error = ref(null);
const images = ref([]);
const selectedImage = ref(null);

// Initialize PageBuilder - it handles everything automatically!
const pageBuilder = new PageBuilderComposable();

// Get modal control functions - professional import pattern!
const { closeMediaLibraryModal } = usePageBuilderModal();

// Simple reactive ref for current image instead of computed
const currentPageBuilderImage = ref(null);

// Computed properties for pageBuilder state
const currentElement = computed(
    () => pageBuilder.pageBuilderStateStore.getElement
);
const currentComponent = computed(
    () => pageBuilder.pageBuilderStateStore.getComponent
);
const allComponents = computed(
    () => pageBuilder.pageBuilderStateStore.getComponents
);

// Fetch images from JSON file
const fetchImages = async () => {
    loading.value = true;
    error.value = null;

    try {
        const response = await fetch("/media-library.json");
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        images.value = data;
    } catch (err) {
        console.error("Error fetching media library:", err);
        error.value = "Failed to load media library. Please try again.";
    } finally {
        loading.value = false;
    }
};

// Handle image click - integrate with page builder media store
const handleImageClick = async (image) => {
    selectedImage.value = image;

    // Follow the exact same pattern as built-in Unsplash component
    // They only set { file } - that's it!
    const imageFile = `/${image.large_path}`;

    // Set current image using PageBuilder methods (if available)
    if (
        pageBuilder.mediaLibraryStore &&
        pageBuilder.mediaLibraryStore.setCurrentImage
    ) {
        // Structure the data to match what updateBasePrimaryImage expects
        pageBuilder.mediaLibraryStore.setCurrentImage({
            currentImage: {
                mediaLibrary: {
                    path: image.path,
                    large_path: image.large_path,
                    // Include other properties that might be needed
                    id: image.id,
                    name: image.name,
                    medium_path: image.medium_path,
                    thumbnail_path: image.thumbnail_path,
                    width: image.width,
                    height: image.height,
                    size: image.size,
                    extension: image.extension,
                },
            },
        });
    }

    if (
        pageBuilder.mediaLibraryStore &&
        pageBuilder.mediaLibraryStore.setCurrentPreviewImage
    ) {
        pageBuilder.mediaLibraryStore.setCurrentPreviewImage(null);
    }

    // Update our local reactive ref for the preview
    currentPageBuilderImage.value = {
        file: imageFile,
        id: image.id,
        name: image.name,
        path: image.path,
        thumbnail_path: image.thumbnail_path,
        medium_path: image.medium_path,
        large_path: image.large_path,
        width: image.width,
        height: image.height,
        size: image.size,
        extension: image.extension,
    };
};

// Apply selected image using PageBuilder's built-in method
const applySelectedImage = async () => {
    if (!selectedImage.value) {
        console.warn("No image selected to apply");
        return;
    }

    // Test: Try calling setBasePrimaryImage directly
    console.log(
        "Called setBasePrimaryImage directly with:",
        selectedImage.value
    );
    await pageBuilder.pageBuilderStateStore.setBasePrimaryImage(
        selectedImage.value
    );

    // Debug: Check pageBuilder state store values
    console.log("getElement:", currentElement.value);
    console.log("getComponent:", currentComponent.value);
    console.log("getComponents:", allComponents.value);

    return;

    try {
        // Ensure the current image is set in the store with proper structure
        pageBuilder.mediaLibraryStore.setCurrentImage({
            currentImage: {
                mediaLibrary: {
                    path: selectedImage.value.path,
                    large_path: selectedImage.value.large_path,
                    id: selectedImage.value.id,
                    name: selectedImage.value.name,
                    medium_path: selectedImage.value.medium_path,
                    thumbnail_path: selectedImage.value.thumbnail_path,
                    width: selectedImage.value.width,
                    height: selectedImage.value.height,
                    size: selectedImage.value.size,
                    extension: selectedImage.value.extension,
                },
            },
        });

        // Use PageBuilder's built-in method to apply the image
        await pageBuilder.updateBasePrimaryImage();
        console.log(
            "Image applied to element successfully",
            selectedImage.value
        );

        // Close modal using professional pattern
        closeMediaLibraryModal();
    } catch (error) {
        console.error("Error applying image to element:", error);
    }
};

onMounted(() => {
    fetchImages();
});
</script>

<style scoped>
/* Custom styles for the media library component */
</style>

<template>
    <div>
        <div class="flex gap-6">
            <!-- Left side: Image grid and states -->
            <div class="flex-1">
                <!-- Loading state -->
                <div v-if="loading" class="text-center py-8">
                    <div
                        class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"
                    ></div>
                    <p class="mt-2 text-gray-600">Loading images...</p>
                </div>

                <!-- Error state -->
                <div v-else-if="error" class="text-center py-8">
                    <p class="text-red-600">{{ error }}</p>
                </div>

                <!-- Images grid -->
                <div
                    v-else
                    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                >
                    <div
                        v-for="image in images"
                        :key="image.id"
                        @click="handleImageClick(image)"
                        class="relative cursor-pointer group rounded-lg overflow-hidden border-2 transition-all duration-200"
                        :class="
                            selectedImage && selectedImage.id === image.id
                                ? 'border-blue-500 ring-2 ring-blue-200'
                                : 'border-gray-200 hover:border-gray-300'
                        "
                    >
                        <div class="aspect-square">
                            <img
                                :src="`/${image.thumbnail_path}`"
                                :alt="image.name || `Image ${image.id}`"
                                class="w-full h-full object-cover"
                            />
                        </div>

                        <!-- Overlay on hover -->
                        <div
                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center"
                        >
                            <div
                                class="opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                            >
                                <span class="text-white text-sm font-medium"
                                    >Select</span
                                >
                            </div>
                        </div>

                        <!-- Selected indicator -->
                        <div
                            v-if="
                                selectedImage && selectedImage.id === image.id
                            "
                            class="absolute top-2 right-2 bg-blue-500 text-white rounded-full p-1"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- No images message -->
                <div
                    v-if="!loading && !error && images.length === 0"
                    class="text-center py-8"
                >
                    <p class="text-gray-500">
                        No images found in your media library.
                    </p>
                </div>
            </div>

            <!-- Right side: Preview panels -->
            <div class="w-80 space-y-4">
                <!-- Current Page Builder Image Preview -->
                <div
                    v-if="
                        currentPageBuilderImage && currentPageBuilderImage.file
                    "
                    class="p-4 bg-blue-50 border border-blue-200 rounded-lg"
                >
                    <h3 class="font-medium text-blue-900 mb-3">
                        Ready to Apply
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-center">
                            <img
                                :src="currentPageBuilderImage.file"
                                :alt="
                                    currentPageBuilderImage.name ||
                                    'Selected image'
                                "
                                class="mx-auto block w-full object-cover object-center cursor-pointer"
                            />
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-blue-800 font-medium mb-1">
                                {{
                                    currentPageBuilderImage.name ||
                                    "Selected Image"
                                }}
                            </p>
                        </div>
                        <div
                            class="grid grid-cols-1 gap-2 text-sm text-gray-600"
                        >
                            <div>
                                <strong>Status:</strong>
                                Ready to apply to current element
                            </div>
                            <div>
                                <strong>Name:</strong>
                                {{ selectedImage.name || "Untitled" }}
                            </div>
                            <div>
                                <strong>Size:</strong>
                                {{ selectedImage.width }}x{{
                                    selectedImage.height
                                }}
                            </div>
                            <div>
                                <strong>Extension:</strong>
                                {{ selectedImage.extension?.toUpperCase() }}
                            </div>
                            <div>
                                <strong>File Size:</strong>
                                {{ selectedImage.size }}kb
                            </div>
                        </div>

                        <!-- Apply button -->
                        <div class="mt-4">
                            <button
                                @click="applySelectedImage"
                                :disabled="!selectedImage"
                                class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                            >
                                Apply to Current Element
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Placeholder when nothing is selected -->
                <div
                    v-if="!selectedImage && !currentPageBuilderImage?.file"
                    class="p-4 bg-gray-50 rounded-lg text-center"
                >
                    <div class="text-gray-400 mb-2">
                        <svg
                            class="w-12 h-12 mx-auto"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">
                        Click an image to preview
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
