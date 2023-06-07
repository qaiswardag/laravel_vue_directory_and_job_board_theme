<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";
</script>

<template>
    <GuestsLayout>
        <Head title="Home" />
        <template #header>
            <div class="myPrimaryMainPageHeaderParagraph">
                <h1 class="myPrimaryMainPageHeaderNotLoggedIn">
                    Privacy Policy
                </h1>
                <p class="myPrimaryMainPageParagraphNotLoggedIn">
                    Discover the ultimate minimalist administration panel with
                    robust and multiple security layers and advanced features
                    like Team Management, Blog and Media Library.
                </p>
            </div>
        </template>
    </GuestsLayout>
</template>
