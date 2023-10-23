<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";
import TemplateListResources from "@/Pages/Guests/TemplateListResources/TemplateListResources.vue";
import { vueFetch } from "use-lightweight-fetch";
</script>

<template>
    <MainLayout>
        <GuestsLayout>
            <Head title="Stores" />
            <template #header>
                <div class="myPrimaryMainPageHeaderParagraph">
                    <h1 class="myPrimaryMainPageHeaderNotLoggedIn">Stores</h1>
                    <p class="myPrimaryMainPageParagraphNotLoggedIn">
                        Discover the ultimate minimalist administration panel
                        with robust and multiple security layers and advanced
                        features

                        <Link :href="route('stores.index')" class="">
                            stores.
                        </Link>
                    </p>
                </div>
            </template>

            <!-- Searchbar # start -->
            <!-- Searchbar # end -->
            <TemplateListResources
                pathList="api.guest.stores.index"
            ></TemplateListResources>
        </GuestsLayout>
    </MainLayout>
</template>
