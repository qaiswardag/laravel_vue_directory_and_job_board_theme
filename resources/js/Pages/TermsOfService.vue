<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";
import PageHeader from "@/Components/Headers/PageHeader.vue";

defineProps({
    terms: String,
});
</script>

<template>
    <Head title="Terms of Service" />

    <MainLayout>
        <GuestsLayout>
            <template #header>
                <div>
                    <PageHeader title="Terms of Service"></PageHeader>
                    <div class="myPrimaryWidthScreenModule">
                        <div class="myPrimaryContentSection" v-html="terms" />
                    </div>
                </div>
            </template>
        </GuestsLayout>
    </MainLayout>
</template>
