<template>
    <div
        class="w-full bg-white inset-x-0 h-[90vh] lg:pt-0 pt-0-z-10 overflow-x-scroll px-4"
    >
        <div id="previewContent">
            <div v-html="htmlPage"></div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";

// router
// const router = useRouter();
// preview content
const htmlPage = ref("");
// get preview item from local storage
htmlPage.value = localStorage.getItem("preview");
// parse
htmlPage.value = JSON.parse(htmlPage.value);
// join
htmlPage.value = htmlPage.value.join("");
</script>
