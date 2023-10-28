<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";

defineProps({
    policy: String,
});
</script>

<template>
    <MainLayout>
        <GuestsLayout>
            <Head title="Home" />
            <template #header>
                <div class="myPrimaryMainPageHeaderParagraph">
                    <h1 class="myPrimaryMainPageHeaderNotLoggedIn">
                        Privacy Policy
                    </h1>
                </div>
                <div class="myPrimaryContentSection" v-html="policy" />
            </template>
        </GuestsLayout>
    </MainLayout>
</template>
