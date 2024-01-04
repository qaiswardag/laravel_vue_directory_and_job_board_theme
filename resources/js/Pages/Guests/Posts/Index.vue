<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";
import Items from "@/Pages/Guests/Items/Items.vue";
import PageHeader from "@/Components/Headers/PageHeader.vue";
</script>

<template>
    <Head title="Campaigns">
        <meta
            head-key="description"
            name="description"
            content="Looking for in-store campaigns? myself.ae is the best site in the United Arab Emirates for finding product promotions."
        />
    </Head>

    <MainLayout>
        <GuestsLayout>
            <template #header>
                <PageHeader title="Campaigns"></PageHeader>
            </template>
            <Items pathList="api.guest.posts.index" nameList="posts"></Items>
        </GuestsLayout>
    </MainLayout>
</template>
