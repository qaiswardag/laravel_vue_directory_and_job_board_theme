<script setup>
import { computed, watch, ref } from "vue";
import { useStore } from "vuex";
import EditorAccordion from "@/Components/Builder/EditorMenu/EditorAccordion.vue";
import MediaLibraryModal from "@/Components/Modals/MediaLibraryModal.vue";
import { PhotoIcon } from "@heroicons/vue/24/outline";
import Designer from "@/composables/Designer";

const props = defineProps({
    team: {
        required: true,
    },
    user: {
        required: true,
    },
});

const store = useStore();
const designer = new Designer(store);

//
// use media library
const showMediaModal = ref(false);
// modal content
const titleMedia = ref("");
const descriptionMedia = ref("");
const firstButtonMedia = ref("");
const secondButtonMedia = ref(null);
const thirdButtonMedia = ref(null);
// set dynamic modal handle functions
const firstMediaButtonFunction = ref(null);
const secondMediaButtonFunction = ref(null);
const thirdMediaButtonFunction = ref(null);

//
//
//
// get current image from store
const getBasePrimaryImage = computed(() => {
    return store.getters["designer/getBasePrimaryImage"];
});
//
//
// image click
const updateImage = function () {
    // open modal to true
    showMediaModal.value = true;

    // set media library modal standards
    titleMedia.value = `Media Library`;
    descriptionMedia.value = null;
    firstButtonMedia.value = "Close";
    secondButtonMedia.value = "Select image";

    // handle click
    firstMediaButtonFunction.value = function () {
        // close media library modal
        showMediaModal.value = false;
    };
    //
    // handle click
    secondMediaButtonFunction.value = function () {
        console.log("clicked on a image save");
        designer.updateBasePrimaryImage();
        // close media library modal
        showMediaModal.value = false;
    };
    //
    // end modal
};
</script>
<template>
    <div v-if="getBasePrimaryImage !== null">
        <img
            class="object-cover object-center w-full"
            :src="getBasePrimaryImage"
            @click="updateImage"
            alt="image"
        />

        <div class="my-4 px-4">
            <button
                @click="updateImage"
                type="button"
                class="myPrimaryButton gap-2 items-center w-full"
            >
                <PhotoIcon class="w-4 h-4 stroke-2"></PhotoIcon>
                Update image
            </button>
        </div>
    </div>
    <MediaLibraryModal
        :user="user"
        :team="team"
        :open="showMediaModal"
        :title="titleMedia"
        :description="descriptionMedia"
        :firstButtonText="firstButtonMedia"
        :secondButtonText="secondButtonMedia"
        :thirdButtonText="thirdButtonMedia"
        @firstMediaButtonFunction="firstMediaButtonFunction"
        @secondMediaButtonFunction="secondMediaButtonFunction"
        @thirdMediaButtonFunction="thirdMediaButtonFunction"
    >
    </MediaLibraryModal>
</template>
