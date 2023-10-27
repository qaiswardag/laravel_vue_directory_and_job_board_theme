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
            <Head title="Jobs" />
            <template #header>
                <div class="myPrimaryMainPageHeaderParagraph">
                    <h1 class="myPrimaryMainPageHeaderNotLoggedIn">Jobs</h1>
                    <p class="myPrimaryMainPageParagraphNotLoggedIn">
                        <Link :href="route('jobs.guest.index')"> jobs. </Link>
                    </p>
                </div>
            </template>

            <!-- Searchbar # start -->
            <!-- Searchbar # end -->
            <Items pathList="api.guest.jobs.index" nameList="jobs"></Items>
        </GuestsLayout>
    </MainLayout>
</template>
