<script setup>
import { ref, computed, onMounted } from "vue";
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
const breadcrumbsLinks = [
    {
        label: "Media",
        route: {
            name: "media.index",
            parameters: props.currentUserTeam.id,
        },
    },
];

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
    firstButtonMedia.value = null;
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
    <LoggedInLayout>
        <Head title="Media" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Media</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <div class="myPrimarySection">
            <MediaLibraryModal
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
                    <label
                        class="block text-sm font-normal leading-6 text-gray-900"
                        >Manage or upload new images
                    </label>
                    <div
                        class="mt-2 mb-6 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
                    >
                        <div class="text-center">
                            <svg
                                class="mx-auto h-12 w-12 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    vector-effect="non-scaling-stroke"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"
                                />
                            </svg>
                            <div
                                class="mt-4 flex text-sm leading-6 text-gray-600"
                            >
                                <PrimaryButton
                                    @click="handleMediaLibrary"
                                    class="mb-4 myPrimaryButton gap-2 items-center"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-4 h-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                                        ></path>
                                    </svg>
                                    Manage & Upload
                                </PrimaryButton>
                            </div>
                            <p class="text-xs leading-5 text-gray-600">
                                PNG, JPG, GIF up to 2MB per file.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="py-12">
                    <div class="mt-6 mb-6">
                        <div class="myHeaderParagraphSection pl-0">
                            <h2 class="myTertiaryHeader">
                                Manage or upload new images
                            </h2>
                            <p class="myPrimaryParagraph font-medium">
                                You are currently viewing the latest images
                                uploaded by your team. These images are sorted
                                by date, with the most recent ones displayed
                                first.
                            </p>
                        </div>

                        <ul
                            role="list"
                            class="grid grid-cols-2 myPrimaryGap sm:grid-cols-3 lg:grid-cols-4"
                        >
                            <li
                                v-for="file in images && images.data"
                                :key="file.id"
                                @click="handleMediaLibrary(file.id)"
                                class="rounded pb-2 cursor-pointer bg-gray-100"
                            >
                                <div
                                    class="group aspect-w-10 aspect-h-7 block w-full overflow-hidden rounded-t bg-gray-100"
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
                                        class="py-3 flex justify-between items-center"
                                    >
                                        <dt class="">Dimensions</dt>
                                        <dd class="">
                                            {{ file.width }} x {{ file.height }}
                                        </dd>
                                    </div>
                                    <div
                                        class="py-3 flex justify-between items-center"
                                    >
                                        <dd class="">
                                            {{ file.name ? file.name : "–" }}
                                        </dd>
                                    </div>
                                    <div
                                        class="py-3 flex justify-between items-center"
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
</template>
