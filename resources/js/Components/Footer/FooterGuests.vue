<script setup>
import { onMounted, ref } from "vue";

const version = ref("");

onMounted(() => {
    version.value = import.meta.env.VITE_APP_VERSION;
});
</script>

<template>
    <footer class="w-full mx-auto px-6 py-4 flex justify-between bg-gray-100">
        <div>
            <p class="myPrimaryParagraph lg:text-sm text-xs">
                Laravel, Vue, and Nuxt, a Page Builder, Listing Directory, Blog,
                and Job Board Theme. | Version: {{ version }}
            </p>
        </div>
        <div class="flex justify-center items-center myPrimaryGap">
            <p class="myPrimaryParagraph lg:text-sm text-xs">
                <a
                    href="https://github.com/qaiswardag/laravel_vue_directory_and_job_board_theme"
                    target="_blank"
                >
                    GitHub
                </a>
            </p>
            <p class="myPrimaryParagraph lg:text-sm text-xs">
                <a
                    href="https://www.linkedin.com/in/qaiswardag/"
                    target="_blank"
                >
                    LinkedIn
                </a>
            </p>
        </div>
    </footer>
</template>
