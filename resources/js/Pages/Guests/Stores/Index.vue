<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";
import Items from "@/Pages/Guests/Items/Items.vue";
import PageHeader from "@/Components/Headers/PageHeader.vue";
</script>

<template>
    <Head title="Stores" />

    <MainLayout>
        <GuestsLayout>
            <template #header>
                <PageHeader title="Stores"></PageHeader>
            </template>
            <Items pathList="api.guest.stores.index" nameList="stores"></Items>
        </GuestsLayout>
    </MainLayout>
</template>
