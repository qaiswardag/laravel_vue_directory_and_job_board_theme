<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";
import TemplateListResources from "@/Pages/Guests/TemplateListResources/TemplateListResources.vue";
</script>

<template>
    <MainLayout>
        <GuestsLayout>
            <Head title="Stores" />
            <TemplateListResources
                pathList="api.guest.stores.index"
                nameList="stores"
            ></TemplateListResources>
        </GuestsLayout>
    </MainLayout>
</template>
