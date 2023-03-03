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
            parameters: 1,
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
});

// tabs
const tabs = [
    { name: "Recently Viewed", href: "#", current: true },
    { name: "Recently Added", href: "#", current: false },
    { name: "Favorited", href: "#", current: false },
];

const images = [
    {
        name: "IMG_4985.HEIC",
        size: "3.9 MB",
        source: "https://images.unsplash.com/photo-1582053433976-25c00369fc93?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
        current: true,
    },
    {
        name: "IMG_4985.HEIC",
        size: "3.9 MB",
        source: "https://images.unsplash.com/photo-1582053433976-25c00369fc93?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
        current: true,
    },
    {
        name: "IMG_4985.HEIC",
        size: "3.9 MB",
        source: "https://images.unsplash.com/photo-1582053433976-25c00369fc93?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
        current: true,
    },
    {
        name: "IMG_4985.HEIC",
        size: "3.9 MB",
        source: "https://images.unsplash.com/photo-1582053433976-25c00369fc93?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
        current: true,
    },
    {
        name: "IMG_4985.HEIC",
        size: "3.9 MB",
        source: "https://images.unsplash.com/photo-1582053433976-25c00369fc93?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
        current: true,
    },
    {
        name: "IMG_4985.HEIC",
        size: "3.9 MB",
        source: "https://images.unsplash.com/photo-1582053433976-25c00369fc93?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
        current: true,
    },
    {
        name: "IMG_4985.HEIC",
        size: "3.9 MB",
        source: "https://images.unsplash.com/photo-1582053433976-25c00369fc93?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
        current: true,
    },
    {
        name: "IMG_4985.HEIC",
        size: "3.9 MB",
        source: "https://images.unsplash.com/photo-1582053433976-25c00369fc93?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
        current: true,
    },
    // More files...
];

const currentFile = {
    name: "IMG_4985.HEIC",
    size: "3.9 MB",
    source: "https://images.unsplash.com/photo-1582053433976-25c00369fc93?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
    information: {
        "Uploaded by": "Marie Culver",
        Created: "June 8, 2020",
        "Last modified": "June 8, 2020",
        Dimensions: "4032 x 3024",
        Resolution: "72 x 72",
    },
};

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
    firstButtonMedia.value = "Close";
    secondButtonMedia.value = "Select image";
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
    <LoggedInLayout title="Team Settings">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Team Settings</h2>
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
            <div class="my-4">
                <PrimaryButton @click="handleUploadImages"
                    >Upload images</PrimaryButton
                >
            </div>
            <div>
                <!-- Main content -->
                <div
                    class="grid grid-cols-12 myPrimaryGap overflow-hidden bg-white rounded-xl"
                >
                    <!-- main gallary overview -->
                    <main class="flex-1 overflow-y-auto col-span-8">
                        <div
                            class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8"
                        >
                            <div class="flex">
                                <h1 class="tertiaryHeader">Photos</h1>
                            </div>

                            <!-- Tabs -->
                            <!-- gallery images - start -->
                            <div>
                                <div class="mt-3 sm:mt-2">
                                    <div class="sm:hidden">
                                        <label for="tabs" class="sr-only"
                                            >Select a tab</label
                                        >
                                        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                                        <select
                                            id="tabs"
                                            name="tabs"
                                            class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 sm:text-sm"
                                        >
                                            <option selected="">
                                                Recently Viewed
                                            </option>
                                            <option>Recently Added</option>
                                            <option>Favorited</option>
                                        </select>
                                    </div>
                                    <div>
                                        <div
                                            class="flex items-center border-b border-gray-200"
                                        >
                                            <nav
                                                class="-mb-px flex flex-1 space-x-6 xl:space-x-8"
                                                aria-label="Tabs"
                                            >
                                                <a
                                                    v-for="tab in tabs"
                                                    :key="tab.id"
                                                    :href="tab.href"
                                                    :aria-current="
                                                        tab.current
                                                            ? 'page'
                                                            : undefined
                                                    "
                                                    :class="[
                                                        tab.current
                                                            ? 'border-emerald-500 text-emerald-600'
                                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                                        'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                                                    ]"
                                                    >{{ tab.name }}</a
                                                >
                                            </nav>
                                        </div>
                                    </div>
                                </div>

                                <!-- Gallery -->
                                <section
                                    class="mt-8 pb-16"
                                    aria-labelledby="gallery-heading"
                                >
                                    <h2 id="gallery-heading" class="sr-only">
                                        Recently viewed
                                    </h2>
                                    <ul
                                        role="list"
                                        class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8"
                                    >
                                        <li
                                            v-for="file in images"
                                            :key="file.name"
                                            class="relative"
                                        >
                                            <div
                                                class="rounded-lg bg-gray-100']"
                                            >
                                                <img
                                                    :src="file.source"
                                                    :alt="file.name"
                                                    class="lg:h-48 xl:w-full h-48 w-full rounded-md object-cover"
                                                />
                                            </div>
                                            <div
                                                class="mt-1 py-2 px-2 pointer-events-none block text-xs text-white bg-gray-800 break-words border border-gray-5 rounded-lg"
                                            >
                                                <p>Name: {{ file.name }}</p>
                                                <p>
                                                    Size:
                                                    {{
                                                        (
                                                            file.size / 1000
                                                        ).toFixed(2)
                                                    }}
                                                    KB
                                                </p>
                                                <p>Width: {{ file.width }}</p>
                                                <p>Height: {{ file.height }}</p>
                                            </div>
                                        </li>
                                    </ul>
                                </section>

                                <hr />
                                <template>
                                    <ul
                                        role="list"
                                        class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8"
                                    >
                                        <li
                                            v-for="file in images"
                                            :key="file.source"
                                            class="relative"
                                        >
                                            <div
                                                class="group aspect-w-10 aspect-h-7 block w-full overflow-hidden rounded-lg bg-gray-100 focus-within:ring-2 focus-within:ring-myPrimaryBrandColor focus-within:ring-offset-2 focus-within:ring-offset-gray-100"
                                            >
                                                <img
                                                    :src="file.source"
                                                    alt=""
                                                    class="pointer-events-none object-cover group-hover:opacity-75"
                                                />
                                                <button
                                                    type="button"
                                                    class="absolute inset-0 focus:outline-none"
                                                >
                                                    <span class="sr-only"
                                                        >View details for
                                                        {{ file.title }}</span
                                                    >
                                                </button>
                                            </div>
                                            <p
                                                class="pointer-events-none mt-2 block truncate text-sm font-medium text-gray-900"
                                            >
                                                {{ file.title }}
                                            </p>
                                            <p
                                                class="pointer-events-none block text-sm font-medium text-gray-500"
                                            >
                                                {{ file.size }}
                                            </p>
                                        </li>
                                    </ul>
                                </template>
                            </div>
                            <!-- gallery images - end -->
                        </div>
                    </main>

                    <!-- details sidebar -->
                    <aside
                        class="overflow-y-auto border-l border-gray-200 bg-white p-8 col-span-4"
                    >
                        <div class="space-y-6 pb-16">
                            <div>
                                <div
                                    class="aspect-h-7 block overflow-hidden rounded-lg"
                                >
                                    <img
                                        :src="currentFile.source"
                                        alt=""
                                        class="object-cover"
                                    />
                                </div>
                                <div
                                    class="mt-4 flex items-start justify-between"
                                >
                                    <div>
                                        <h2
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            <span class="sr-only"
                                                >Details for </span
                                            >{{ currentFile.name }}
                                        </h2>
                                        <p
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            {{ currentFile.size }}
                                        </p>
                                    </div>
                                    <button
                                        type="button"
                                        class="ml-4 flex h-8 w-8 items-center justify-center rounded-full bg-white text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    >
                                        <span class="sr-only">Favorite</span>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">
                                    Information
                                </h3>
                                <dl
                                    class="mt-2 divide-y divide-gray-200 border-t border-b border-gray-200"
                                >
                                    <div
                                        v-for="key in Object.keys(
                                            currentFile.information
                                        )"
                                        :key="key"
                                        class="flex justify-between py-3 text-sm font-medium"
                                    >
                                        <dt class="text-gray-500">{{ key }}</dt>
                                        <dd
                                            class="whitespace-nowrap text-gray-900"
                                        >
                                            {{ currentFile.information[key] }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                            <div class="flex items-center myPrimaryGap">
                                <button type="button" class="myPrimaryButton">
                                    Download
                                </button>
                                <button
                                    type="button"
                                    class="myPrimaryDeleteButton"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </LoggedInLayout>
</template>
