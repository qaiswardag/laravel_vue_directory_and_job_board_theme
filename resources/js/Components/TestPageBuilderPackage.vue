<script setup>
import CustomMediaLibraryComponent from "../../../../laravel_vue_directory_and_job_board_theme/ComponentsPageBuilder/CustomMediaLibraryComponent.vue";
import CustomSearchComponent from "../../../../laravel_vue_directory_and_job_board_theme/ComponentsPageBuilder/CustomSearchComponent.vue";
// Import external package CSS
import {
    PageBuilder,
    PageBuilderClass,
    usePageBuilderStateStore,
    createPinia,
} from "vue-website-page-builder";
// Import external package CSS
import "vue-website-page-builder/style.css";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import { ref, onMounted } from "vue";

// Create standalone PageBuilder instance
const internalPinia = createPinia();
const pageBuilderStateStore = usePageBuilderStateStore(internalPinia);
const pageBuilderClass = new PageBuilderClass(pageBuilderStateStore);

const openPageBuilder = ref(false);
// use dynamic model
const typeModal = ref("");
const gridColumnModal = ref(Number(1));
const titleModal = ref("");
const descriptionModal = ref("");
const firstButtonModal = ref("");
const secondButtonModal = ref(null);
const thirdButtonModal = ref(null);
// set dynamic modal handle functions
const firstModalButtonFunction = ref(null);
const secondModalButtonFunction = ref(null);
const thirdModalButtonFunction = ref(null);

const togglePageBuilder = function (e) {
    openPageBuilder.value = true;
    typeModal.value = "default";
    gridColumnModal.value = 2;
    titleModal.value = "Test page builder package";
    descriptionModal.value = null;
    firstButtonModal.value = "Close";
    secondButtonModal.value = "Close page builder";

    // handle click
    firstModalButtonFunction.value = function () {
        // openPageBuilder.value = false;
    };
    secondModalButtonFunction.value = function () {
        openPageBuilder.value = false;
    };
};

const postIs = `
<section data-componentid=\"f4b6dfb6-05fd-4d46-835c-f79d212d5314\">\n<div class=\"w-full md:pt-12 md:pb-12 pt-4 pb-4 lg:px-4 px-2\"><div class=\"mx-auto max-w-7xl\"><div class=\"myPrimaryGap grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-2\"> <div class=\"flex-1 py-2\"> <img class=\"object-cover w-full object-top aspect-[9/16] smooth-transition\" src=\"/storage/uploads/2/2024/05/00058_chanel_resort_2025_credit_gorunway_f1htzhg9lfzjp1715525583_large.webp\" alt=\"provider\">  </div> <div class=\"flex-1 py-2\"> <img class=\"object-cover w-full object-top aspect-[9/16] smooth-transition\" src=\"/storage/uploads/2/2024/05/00011_chanel_resort_2025_credit_gorunway_ib8radavtvaqsx1715525588_large.webp\" alt=\"provider\">  </div>   </div> </div> </div>\n</section>","id":"f4b6dfb6-05fd-4d46-835c-f79d212d5314"},{"html_code":"<section data-componentid=\"0bfda7cc-e2ce-4bb4-be3c-e6d3f4c7800d\">\n        <div class=\"relative py-4\">\n        <div class=\"mx-auto max-w-7xl lg:px-4 px-2\">\n        <div><p><strong>T</strong>he Chanel brand has been an iconic label since the moment it was born. There’s nothing in the brand’s collections that does not shout “Elegant.” From the famous Chanel bags made from the softest leather to the bicolor slingback Chanel shoes and lesser-known Chanel earrings, they’ve got it all.<br><br>The brand was born at the beginning of the 20th Century, and for over a century, it has led the fashion world. The Chanel brand logo has become an unmistakable one. It does not matter what you’re looking to get. <em>Chanel sneakers. Chanel sandals. Chanel espadrilles. Chanel makeup. Or maybe a high-quality Chanel wallet. </em>The French Maison offers it all.<br><br>I remember strolling on the Champs-Élysées in Paris, and wondering what it would be like to have a pretty Chanel mini Coco handle on my arm. I’m pretty sure it would have felt amazing. The Chanel logo is still one that makes my heart beat, even if I’m very much interested in sustainable, comfortable fashion brands too now. If anyone tells you that Coco Chanel dresses have been, don’t listen. They’ve been around since last century, and you’d best believe they’re here to stay.</p><p><br>By the way, I’m not going to talk about the Coco Chanel net worth here. In this article, I prefer to highlight all<strong> the innovations and creative designs we owe the great Gabrielle Chanel</strong>; from Chanel shoes to Chanel Coco Handle Mini. I promise – once you’re done reading this article, you’ll know everything there is to know about one of the most outstanding French fashion brands ever existed!</p></div>\n        </div>\n        </div>\n        </section>","id":"0bfda7cc-e2ce-4bb4-be3c-e6d3f4c7800d"},{"html_code":"<section data-componentid=\"a161f639-5f37-43fa-9080-839791542280\">\n<div class=\"w-full md:pt-12 md:pb-12 pt-4 pb-4 lg:px-4 px-2\"><div class=\"mx-auto max-w-7xl\"><div class=\"myPrimaryGap grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-2\"> <div class=\"flex-1 py-2\"> <img class=\"object-cover w-full object-top aspect-[9/16] smooth-transition\" src=\"/storage/uploads/2/2024/05/00022_chanel_resort_2025_credit_gorunway_ji3ilzjqxf61715525586_large.webp\" alt=\"provider\">  </div> <div class=\"flex-1 py-2\"> <img class=\"object-cover w-full object-top aspect-[9/16] smooth-transition\" src=\"/storage/uploads/2/2024/05/00024_chanel_resort_2025_credit_gorunway_skpregry1715525580_large.webp\" alt=\"provider\">  </div>   </div> </div> </div>\n</section>","id":"a161f639-5f37-43fa-9080-839791542280"}]<section data-componentid="f4b6dfb6-05fd-4d46-835c-f79d212d5314">
  <div class="w-full md:pt-12 md:pb-12 pt-4 pb-4 lg:px-4 px-2">
    <div class="mx-auto max-w-7xl">
      <div class="myPrimaryGap grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-2">
        <div class="flex-1 py-2">
          <img class="object-cover w-full object-top aspect-[9/16] smooth-transition" src="/storage/uploads/2/2024/05/00058_chanel_resort_2025_credit_gorunway_f1htzhg9lfzjp1715525583_large.webp" alt="provider">
        </div>
        <div class="flex-1 py-2">
          <img class="object-cover w-full object-top aspect-[9/16] smooth-transition" src="/storage/uploads/2/2024/05/00011_chanel_resort_2025_credit_gorunway_ib8radavtvaqsx1715525588_large.webp" alt="provider">
        </div>
      </div>
    </div>
  </div>
</section>

<section data-componentid="0bfda7cc-e2ce-4bb4-be3c-e6d3f4c7800d">
  <div class="relative py-4">
    <div class="mx-auto max-w-7xl lg:px-4 px-2">
      <div>
        <p><strong>T</strong>he Chanel brand has been an iconic label since the moment it was born. There’s nothing in the brand’s collections that does not shout “Elegant.” From the famous Chanel bags made from the softest leather to the bicolor slingback Chanel shoes and lesser-known Chanel earrings, they’ve got it all.<br><br>The brand was born at the beginning of the 20th Century, and for over a century, it has led the fashion world. The Chanel brand logo has become an unmistakable one. It does not matter what you’re looking to get. <em>Chanel sneakers. Chanel sandals. Chanel espadrilles. Chanel makeup. Or maybe a high-quality Chanel wallet. </em>The French Maison offers it all.<br><br>I remember strolling on the Champs-Élysées in Paris, and wondering what it would be like to have a pretty Chanel mini Coco handle on my arm. I’m pretty sure it would have felt amazing. The Chanel logo is still one that makes my heart beat, even if I’m very much interested in sustainable, comfortable fashion brands too now. If anyone tells you that Coco Chanel dresses have been, don’t listen. They’ve been around since last century, and you’d best believe they’re here to stay.</p>
        <p><br>By the way, I’m not going to talk about the Coco Chanel net worth here. In this article, I prefer to highlight all<strong> the innovations and creative designs we owe the great Gabrielle Chanel</strong>; from Chanel shoes to Chanel Coco Handle Mini. I promise – once you’re done reading this article, you’ll know everything there is to know about one of the most outstanding French fashion brands ever existed!</p>
      </div>
    </div>
  </div>
</section>

<section data-componentid="a161f639-5f37-43fa-9080-839791542280">
  <div class="w-full md:pt-12 md:pb-12 pt-4 pb-4 lg:px-4 px-2">
    <div class="mx-auto max-w-7xl">
      <div class="myPrimaryGap grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-2">
        <div class="flex-1 py-2">
          <img class="object-cover w-full object-top aspect-[9/16] smooth-transition" src="/storage/uploads/2/2024/05/00022_chanel_resort_2025_credit_gorunway_ji3ilzjqxf61715525586_large.webp" alt="provider">
        </div>
        <div class="flex-1 py-2">
          <img class="object-cover w-full object-top aspect-[9/16] smooth-transition" src="/storage/uploads/2/2024/05/00024_chanel_resort_2025_credit_gorunway_skpregry1715525580_large.webp" alt="provider">
        </div>
      </div>
    </div>
  </div>
</section>
`;

onMounted(() => {
    pageBuilderClass.loadExistingContent(postIs);
});
</script>

<template>
    <div>
        <div class="p-6 bg-red-100">
            <h1 class="text-2xl font-bold mb-4">
                Create New Post with Page Builder
            </h1>
            <div class="py-10">
                <p>
                    <button
                        class="myPrimaryButton"
                        @click="togglePageBuilder()"
                    >
                        Open page builder
                    </button>
                </p>
            </div>
            <DynamicModal
                :show="openPageBuilder"
                :type="typeModal"
                :gridColumnAmount="gridColumnModal"
                :title="titleModal"
                :description="descriptionModal"
                :firstButtonText="firstButtonModal"
                :secondButtonText="secondButtonModal"
                :thirdButtonText="thirdButtonModal"
                @firstModalButtonFunction="firstModalButtonFunction"
                @secondModalButtonFunction="secondModalButtonFunction"
                @thirdModalButtonFunction="thirdModalButtonFunction"
                maxWidth="max-2xl"
            >
                <header></header>
                <main>
                    <PageBuilder />
                </main>
            </DynamicModal>

            <div class="mt-40 py-10 border-t-2 border-black">
                <h1 class="text-2xl font-bold mb-4">
                    Update existing Post with Page Builder
                </h1>
                <PageBuilder
                    :configPageBuilder="{
                        updateOrCreate: {
                            formType: 'update',
                            createNewResourceFormName: 'post',
                        },
                        pageBuilderLogo: {
                            src: '/logo/logo.svg',
                        },
                        userForPageBuilder: { name: 'John Doe' },
                        resourceData: {
                            title: 'Demo Article',
                            id: 1,
                        },
                        userSettings: {
                            theme: 'pink',
                            language: 'sw',
                            autoSave: true,
                        },
                    }"
                    :CustomMediaLibraryComponent="CustomMediaLibraryComponent"
                    :CustomSearchComponent="CustomSearchComponent"
                />
            </div>
        </div>
    </div>
</template>
