<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";
import PageHeader from "@/Components/Headers/PageHeader.vue";

defineProps({
    policy: String,
});
</script>

<template>
    <MainLayout>
        <GuestsLayout>
            <Head title="Home" />
            <template #header>
                <div>
                    <PageHeader title="Privacy Policy"></PageHeader>
                    <div class="myPrimaryContentSection" v-html="policy" />
                </div>
            </template>
        </GuestsLayout>
    </MainLayout>
</template>
