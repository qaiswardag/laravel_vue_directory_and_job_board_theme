<script setup>
const version = __APP_VERSION__;
</script>

<template>
    <footer class="w-full mx-auto px-6 py-4 flex justify-between bg-gray-100">
        <div>
            <p class="myPrimaryParagraph lg:text-sm text-xs">
                Laravel, Vue, and Nuxt, a Page Builder, Listing Directory, Blog,
                and Job Board Theme.
            </p>
            <p class="myPrimaryParagraph mt-2">
                <span class="text-myPrimaryDarkGrayColor text-xs">{{
                    version
                }}</span>
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
