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
            <template #header>
                <div class="myPrimaryMainPageHeaderParagraph">
                    <h1 class="myPrimaryMainPageHeaderNotLoggedIn">Posts</h1>
                    <p class="myPrimaryMainPageParagraphNotLoggedIn">
                        <Link :href="route('posts.guest.index')"> posts. </Link>
                    </p>
                </div>
            </template>

            <!-- Searchbar # start -->
            <!-- Searchbar # end -->
            <Items pathList="api.guest.posts.index" nameList="posts"></Items>
        </GuestsLayout>
    </MainLayout>
</template>
