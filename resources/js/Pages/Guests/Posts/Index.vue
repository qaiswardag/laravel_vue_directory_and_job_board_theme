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
            <Head title="Posts" />
            <Items pathList="api.guest.posts.index" nameList="posts"></Items>
        </GuestsLayout>
    </MainLayout>
</template>
