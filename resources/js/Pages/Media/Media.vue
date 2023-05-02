<script setup>
import { ref, computed, onMounted } from "vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import { router } from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import MediaLibraryModal from "@/Components/Modals/MediaLibraryModal.vue";

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

const handleUploadImages = function () {
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
    <LoggedInLayout title="Media">
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
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Manage or upload new images
                    </label>
                    <div
                        class="mt-2 mb-6 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
                    >
                        <div class="text-center">
                            <div
                                class="mt-4 flex text-sm leading-6 text-gray-600"
                            >
                                <PrimaryButton
                                    @click="handleUploadImages"
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

                <div class="bg-red-50 py-8 px-4 my-6 rounded-md">
                    <div>
                        <p class="myPrimaryParagraph">
                            Manage or upload new images to your collection to
                            keep your visual assets up-to-date. You can easily
                            organize and sort your images, and quickly find the
                            ones you need with our search and filter tools.
                        </p>
                        <p class="myPrimaryParagraph mt-8 mb-4">
                            With the ability to upload new images, you can
                            ensure that your collection is always expanding with
                            fresh, high-quality visual content that represents
                            your brand and meets your business needs. Whether
                            you need to update your website, social media
                            profiles, or marketing materials, managing and
                            uploading new images is a simple and effective way
                            to stay ahead of the competition.
                        </p>
                    </div>

                    <div class="mt-6 mb-6">
                        <p class="myPrimaryParagraph mt-8 mb-4">
                            You are currently viewing the latest 16 images
                            uploaded by your team. These images are sorted by
                            date, with the most recent ones displayed first.
                        </p>
                        <ul
                            role="list"
                            class="grid grid-cols-2 myPrimaryGap sm:grid-cols-3 lg:grid-cols-4"
                        >
                            <li
                                v-for="file in images"
                                :key="file.id"
                                class="relative border border-myPrimaryMediumGrayColor p-4 rounded"
                            >
                                <div
                                    class="group aspect-w-10 aspect-h-7 block w-full overflow-hidden rounded-lg bg-gray-100 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100"
                                >
                                    <img
                                        :src="`/uploads/${file.path}`"
                                        alt=""
                                        class="w-full pointer-events-none object-cover group-hover:opacity-75"
                                    />
                                </div>

                                <dl
                                    class="myPrimaryParagraph text-xs mt-2 border-b border-myPrimaryMediumGrayColor divide-y divide-myPrimaryMediumGrayColor"
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
    </LoggedInLayout>
</template>
