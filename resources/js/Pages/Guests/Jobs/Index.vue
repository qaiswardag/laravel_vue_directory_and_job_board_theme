<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";
import Items from "@/Pages/Guests/Items/Items.vue";
import PageHeader from "@/Components/Headers/PageHeader.vue";
</script>

<template>
    <MainLayout>
        <GuestsLayout>
            <Head title="Jobs" />
            <template #header>
                <PageHeader title="Jobs"></PageHeader>
            </template>
            <Items pathList="api.guest.jobs.index" nameList="jobs"></Items>
        </GuestsLayout>
    </MainLayout>
</template>
