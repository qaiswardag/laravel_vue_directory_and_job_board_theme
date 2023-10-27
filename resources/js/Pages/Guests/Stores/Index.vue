<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";
import Items from "@/Pages/Guests/Items/Items.vue";
</script>

<template>
    <MainLayout>
        <GuestsLayout>
            <Head title="Stores" />
            <Items pathList="api.guest.stores.index" nameList="stores"></Items>
        </GuestsLayout>
    </MainLayout>
</template>
