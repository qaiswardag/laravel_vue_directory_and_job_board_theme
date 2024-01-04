<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";
import Items from "@/Pages/Guests/Items/Items.vue";
import PageHeader from "@/Components/Headers/PageHeader.vue";
</script>

<template>
    <Head title="Jobs">
        <meta
            head-key="description"
            name="description"
            content="Looking for a job in fashion? myself.ae is the United Arab Emirates best site for finding jobs in the fashion, luxury and retail industries. Start your career here!"
        />
    </Head>

    <MainLayout>
        <GuestsLayout>
            <template #header>
                <PageHeader title="Jobs"></PageHeader>
            </template>
            <Items pathList="api.guest.jobs.index" nameList="jobs"></Items>
        </GuestsLayout>
    </MainLayout>
</template>
