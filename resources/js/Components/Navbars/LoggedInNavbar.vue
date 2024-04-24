<script setup>
import SlideOverNotifications from "@/Components/Sidebars/SlideOverNotifications.vue";
import SlideOverPrimaryMenu from "@/Components/Sidebars/SlideOverPrimaryMenu.vue";
import { onMounted, ref } from "vue";
import SearchAnythingModal from "@/Components/Modals/SearchAnythingModal.vue";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import { usePage } from "@inertiajs/vue3";

import DynamicMenuModal from "@/Components/Modals/DynamicMenuModal.vue";

// slide over
const showNotificationsSlideOver = ref(false);
const showPrimaryMenuSlideOver = ref(false);

// modal search anything
const modalShowSearchAnything = ref(false);

// modal menu content
const modalShowAccountMenu = ref(false);
// set menu modal handle functions
const firstModalMenuButtonFunction = ref(null);

// modal content
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

const handleMenuUserTeamModal = () => {
    // handle show modal for unique content
    modalShowAccountMenu.value = true;
    // handle click
    firstModalMenuButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowAccountMenu.value = false;
    };
    // end menu modal
};

// serach anything modal button (close)
const searchAnythingModalButton = function () {
    modalShowSearchAnything.value = false;
};

// handle serach anything click
const handleSearchAnything = function () {
    modalShowSearchAnything.value = true;
};

// handle primary slideoer menu
const handlePrimaryMenuSlideOver = function () {
    showPrimaryMenuSlideOver.value = true;
};
// handle primary menu window
const primaryMenuSlideOverButton = function () {
    showPrimaryMenuSlideOver.value = false;
};
// handle notifications window
const handleNotificationsSlideOver = function () {
    showNotificationsSlideOver.value = true;
};
// handle notifications window
const notificationsSlideOverButton = function () {
    showNotificationsSlideOver.value = false;
};

const goToHome = function () {
    if (
        usePage() &&
        usePage().props &&
        usePage().props.environment === "local"
    ) {
        window.location.href = "http://localhost:3000";
    }
    if (
        usePage() &&
        usePage().props &&
        usePage().props.environment !== "local"
    ) {
        window.location.href = "https://www.myissue.io"; https://www.myissue.io Laravel and Vue Directory & Job Board Theme
    }
};
</script>
<template>
    <SearchAnythingModal
        :open="modalShowSearchAnything"
        @searchAnythingModalButton="searchAnythingModalButton"
    >
    </SearchAnythingModal>
    <SlideOverNotifications
        :open="showNotificationsSlideOver"
        @notificationsSlideOverButton="notificationsSlideOverButton"
    >
    </SlideOverNotifications>
    <SlideOverPrimaryMenu
        :open="showPrimaryMenuSlideOver"
        @primaryMenuSlideOverButton="primaryMenuSlideOverButton"
    >
    </SlideOverPrimaryMenu>
    <DynamicMenuModal
        v-if="$page.props !== null && $page.props.user !== null"
        :show="modalShowAccountMenu"
        @firstModalMenuButtonFunction="firstModalMenuButtonFunction"
    >
    </DynamicMenuModal>
    <!-- search anything - start -->

    <div class="flex flex-1">
        <div class="ml-4 flex items-center md:ml-6 gap-8">
            <form class="w-full flex md:ml-0" @submit.prevent>
                <div
                    class="relative w-full text-myPrimaryDarkGrayColor focus-within:text-myPrimaryDarkGrayColor"
                >
                    <div
                        class="absolute inset-y-0 left-0 flex items-center pointer-events-none pl-3"
                    >
                        <span class="material-symbols-outlined"> search </span>
                    </div>
                    <input
                        id="search-field"
                        class="text-sm cursor-pointer block w-full h-full pl-10 pr-3 border-transparent placeholder-gray-500 focus:outline-none border-0 focus:ring-0 font-normal text-myPrimaryDarkGrayColor rounded-md py-4 px-3"
                        @click.prevent="handleSearchAnything"
                        placeholder="Search anything..."
                        readonly
                        type="search"
                        autocomplete="off"
                        name="search"
                    />
                </div>
            </form>
        </div>
    </div>
    <!-- search anything - end -->
    <header class="w-6/1 text-sm">
        <nav
            class="mx-auto flex gap-4 max-w-7xl items-center justify-end px-6 lg:px-8"
            aria-label="Global"
        >
            <button @click="goToHome" type="button">
                <div
                    class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white hover:fill-white focus-visible:ring-0"
                >
                    <span class="myMediumIcon material-symbols-outlined">
                        home
                    </span>
                </div>
            </button>

            <button
                v-if="
                    $page.props.user.user_photo &&
                    Array.isArray($page.props.user.user_photo) &&
                    $page.props.user.user_photo.length !== 0
                "
                type="button"
                @click="handleMenuUserTeamModal"
                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
            >
                <div class="h-10 w-10 flex-shrink-0">
                    <ThumbnailSmallImageSlider
                        :images="$page.props.user.user_photo"
                        imageSize="thumbnail_path"
                        imageHeight="h-10 min-h-10 max-h-10"
                        imageWidth="w-10 min-w-10 max-w-10 object-cover"
                        :roundedFull="true"
                    ></ThumbnailSmallImageSlider>
                </div>
            </button>
            <button
                v-if="
                    $page.props.user.user_photo &&
                    Array.isArray($page.props.user.user_photo) &&
                    $page.props.user.user_photo.length === 0
                "
                @click="handleMenuUserTeamModal"
                type="button"
                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
            >
                <span class="myMediumIcon material-symbols-outlined">
                    person
                </span>
            </button>

            <button
                @click="handleNotificationsSlideOver"
                type="button"
                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white hover:fill-white focus-visible:ring-0"
            >
                <span class="sr-only">View notifications</span>
                <span class="myMediumIcon material-symbols-outlined">
                    notifications
                </span>
            </button>

            <button
                @click="handlePrimaryMenuSlideOver"
                type="button"
                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
            >
                <span class="sr-only">View Menu</span>
                <span class="myMediumIcon material-symbols-outlined">
                    drag_handle
                </span>
            </button>
        </nav>
    </header>
</template>
