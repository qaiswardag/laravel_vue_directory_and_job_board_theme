<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";

defineProps({
    terms: String,
});
</script>

<template>
    <MainLayout>
        <GuestsLayout>
            <Head title="Home" />
            <template #header>
                <div>
                    <h1 class="myPrimaryHeader text-center">
                        Terms of Service
                    </h1>
                    <div class="myPrimaryContentSection" v-html="terms" />
                </div>
            </template>
        </GuestsLayout>
    </MainLayout>
</template>
