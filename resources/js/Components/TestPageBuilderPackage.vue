<script setup>
import MediaLibraryComponent from "../../../../laravel_vue_directory_and_job_board_theme/ComponentsPageBuilder/CustomMediaLibraryComponent.vue";
import SearchComponent from "../../../../laravel_vue_directory_and_job_board_theme/ComponentsPageBuilder/CustomSearchComponent.vue";
// Import external package CSS
import { PageBuilder } from "vue-website-page-builder";
// Import external package CSS
import "vue-website-page-builder/style.css";

// Test data (optional - for future use)
const testUser = {
    id: 1,
    first_name: "Test",
    last_name: "User",
    email: "test@example.com",
};

const testResource = {
    id: 1,
    name: "My Blog Post",
};
</script>

<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-4">
            Testing External Page Builder Package
        </h1>
        <PageBuilder
            :CustomMediaLibraryComponent="MediaLibraryComponent"
            :CustomSearchComponent="SearchComponent"
            updateOrCreate="create"
        />
    </div>
</template>
