<script setup>
import SlideOverNotifications from "@/Components/Sidebars/SlideOverNotifications.vue";
import SlideOverPrimaryMenu from "@/Components/Sidebars/SlideOverPrimaryMenu.vue";
import { onMounted, ref } from "vue";
import SearchAnythingModal from "@/Components/Modals/SearchAnythingModal.vue";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import { router } from "@inertiajs/vue3";

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

const appFrontendUrl = ref(null);

const goToHome = function () {
    if (appFrontendUrl.value) {
        window.location.href = appFrontendUrl.value;
    }
};

const handleLogout = () => {
    router.post(route("logout"));
};

onMounted(() => {
    appFrontendUrl.value = import.meta.env.VITE_FRONTEND_APP_URL;
});
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

    <div class="flex flex-1 border-r border-myPrimaryLightMediumGrayColor">
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
    <header class="text-sm flex justify-between overflow-x-auto">
        <nav
            class="mx-auto flex gap-4 max-w-7xl items-center justify-end px-6 lg:px-8 mt-2"
            aria-label="Global"
        >
            <button
                @click="goToHome"
                type="button"
                class="myPrimaryMenuTextButton text-[12.5px] gap-2 text-nowrap pl-2 pr-3 w-full h-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
            >
                <span class="myMediumIcon material-symbols-outlined">
                    home
                </span>
                <span> Start </span>
            </button>

            <button
                v-if="
                    $page.props.user.user_photo &&
                    Array.isArray($page.props.user.user_photo) &&
                    $page.props.user.user_photo.length !== 0
                "
                type="button"
                @click="handleMenuUserTeamModal"
                class="myPrimaryMenuTextButton text-[12.5px] gap-2 text-nowrap flex justify-center items-center py-0.5 border-2 border-myPrimaryLinkColor hover:bg-myPrimaryLinkColor hover:bg-opacity-10 pl-2 pr-3"
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
                <span> Account </span>
            </button>
            <button
                v-if="
                    $page.props.user.user_photo &&
                    Array.isArray($page.props.user.user_photo) &&
                    $page.props.user.user_photo.length === 0
                "
                @click="handleMenuUserTeamModal"
                type="button"
                class="myPrimaryMenuTextButton text-[12.5px] gap-2 text-nowrap flex justify-center items-center py-0.5 border-2 border-myPrimaryLinkColor hover:bg-myPrimaryLinkColor hover:bg-opacity-10 pl-2 pr-3"
            >
                <div
                    class="h-10 min-h-10 max-h-10 w-10 min-w-10 max-w-10 flex justify-center items-center mx-6"
                >
                    <span class="myMediumIcon material-symbols-outlined">
                        person
                    </span>
                    <span> Account </span>
                </div>
            </button>

            <button
                @click="handleNotificationsSlideOver"
                type="button"
                class="myPrimaryMenuTextButton text-[12.5px] gap-2 text-nowrap pl-2 pr-3 w-full h-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
            >
                <span class="sr-only">View notifications</span>
                <span class="myMediumIcon material-symbols-outlined">
                    notifications
                </span>
                <span> Notifications </span>
            </button>

            <form @submit.prevent="handleLogout">
                <button
                    @click="handleLogout"
                    type="button"
                    class="myPrimaryMenuTextButton text-[12.5px] gap-2 text-nowrap pl-2 pr-3 w-full h-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                >
                    <span class="myMediumIcon material-symbols-outlined">
                        arrow_forward
                    </span>
                    <span> Sign out </span>
                </button>
            </form>

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
