<script setup>
import { ref, computed, onMounted } from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import { router } from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import MediaLibraryModal from "@/Components/Modals/MediaLibraryModal.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";
import { useStore } from "vuex";

// store
const store = useStore();

import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";

//
const props = defineProps({
    currentUserTeam: {
        required: true,
    },
    user: {
        required: true,
    },
    images: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "Media",
        route: {
            name: "media.index",
            parameters: props.currentUserTeam.id,
        },
    },
];

// media library modal

// use media library
const showMediaLibraryModal = ref(false);
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

const handleMediaLibrary = function (imageID) {
    if (imageID && typeof imageID === "number") {
        // dispatch
        store.dispatch("mediaLibrary/loadImage", {
            mediaLibraryId: imageID,
            teamId: props.currentUserTeam.id,
        });
    }
    // handle show media library modal
    showMediaLibraryModal.value = true;

    // set media library modal standards
    titleMedia.value = "Media Library";
    descriptionMedia.value = null;
    firstButtonMedia.value = "Close";
    secondButtonMedia.value = null;
    thirdButtonMedia.value = null;
    // handle click
    firstMediaButtonFunction.value = function () {
        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    //
    // handle click
    secondMediaButtonFunction.value = function () {
        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    // end modal
};
</script>

<template>
    <Head title="Media" />

    <MainLayout>
        <LoggedInLayout>
            <template #header> Media </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <div class="myPrimarySection">
                <MediaLibraryModal
                    :forUserNotTeam="false"
                    :user="user"
                    :team="currentUserTeam"
                    :open="showMediaLibraryModal"
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

                <div>
                    <div class="col-span-full">
                        <div
                            class="myPrimaryParagraph rounded-full bg-myPrimaryLightGrayColor text-center w-full inset-0 text-base pt-6 pb-6 px-2 flex items-center justify-center"
                        >
                            <div class="text-center">
                                <div class="my-8">
                                    <PrimaryButton
                                        @click="handleMediaLibrary"
                                        class="myPrimaryButton gap-3 py-4"
                                    >
                                        <span class="material-symbols-outlined">
                                            cloud_upload
                                        </span>
                                        Manage & Upload Images
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="py-12">
                        <div class="mt-6 mb-6">
                            <ul
                                role="list"
                                class="grid grid-cols-2 myPrimaryGap sm:grid-cols-3 lg:grid-cols-4"
                            >
                                <li
                                    v-for="file in images && images.data"
                                    :key="file.id"
                                    @click="handleMediaLibrary(file.id)"
                                    class="pb-2 cursor-pointer border border-myPrimaryLightMediumGrayColor rounded-lg"
                                >
                                    <div
                                        class="group aspect-w-10 aspect-h-7 block w-full overflow-hidden rounded-t bg-myPrimaryLightGrayColor"
                                    >
                                        <img
                                            :src="`/storage/uploads/${file.medium_path}`"
                                            alt=""
                                            class="w-full pointer-events-none object-cover group-hover:opacity-75"
                                        />
                                    </div>

                                    <dl
                                        class="myPrimaryParagraph text-xs mt-2 px-2 divide-y"
                                    >
                                        <div
                                            class="py-3 flex justify-between items-center gap-2 break-all"
                                        >
                                            <dt class="">Name</dt>
                                            <dd class="">
                                                {{
                                                    file.name ? file.name : "—"
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            class="py-3 flex justify-between items-center gap-2 break-all"
                                        >
                                            <dt class="">Dimensions</dt>
                                            <dd class="">
                                                {{ file.width }} x
                                                {{ file.height }}
                                            </dd>
                                        </div>
                                        <div
                                            class="py-3 flex justify-between items-center gap-2 break-all"
                                        >
                                            <dt class="">Size</dt>
                                            <dd class="">{{ file.size }} KB</dd>
                                        </div>
                                    </dl>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <Pagination :links="images?.links ? images.links : []"></Pagination>
        </LoggedInLayout>
    </MainLayout>
</template>
