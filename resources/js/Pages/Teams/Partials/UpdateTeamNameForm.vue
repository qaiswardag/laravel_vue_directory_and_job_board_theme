<script setup>
import { useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import AvatarCardCenterSmall from "@/Components/Avatars/AvatarCardCenterSmall.vue";
import { ref, computed } from "@vue/reactivity";
import MediaLibraryModal from "@/Components/Modals/MediaLibraryModal.vue";
import { useStore } from "vuex";
import { Switch } from "@headlessui/vue";
import NotificationsFixedBottom from "@/Components/Modals/NotificationsFixedBottom.vue";
import slugify from "slugify";
import config from "@/utils/config";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";

import {
    CheckIcon,
    ChevronUpDownIcon,
    LockClosedIcon,
    LockOpenIcon,
    SquaresPlusIcon,
    TrashIcon,
    UserIcon,
    UserPlusIcon,
    XMarkIcon,
    Squares2X2Icon,
    NewspaperIcon,
    PhotoIcon,
    MapPinIcon,
    GlobeAmericasIcon,
    PlusIcon,
} from "@heroicons/vue/24/outline";

import { onBeforeMount, onMounted, watch } from "vue";

// store
const store = useStore();

const props = defineProps({
    team: Object,
    permissions: Object,
});

// $page.props.user.current_team.coverImagesWithLogos.logos;

const handleUpdateTeam = function () {
    updateTeam();

    // end modal
};

const isSlugEditable = ref(true);
const slugValueTeamName = ref("");
const slugValueCustom = ref("");

const postForm = useForm({
    name: "",
    slug: "",
    team_id: props.team.id,
    public: true,
    cover_image: [],
    logo: [],
});

// The above code uses the watch function from Vue 3 to watch for changes to the
// slugValueCustom property and update the postForm.slug field with the new value

const watchSlugInputChanges = function () {
    watch(
        () => slugValueCustom.value,
        (newValue) => {
            if (isSlugEditable.value === true) {
                postForm.slug = slugify(
                    slugValueCustom.value,
                    config.slugifyOptions
                );
            }
        },
        { immediate: true }
    );
    watch(
        () => postForm.name,
        (newValue) => {
            if (isSlugEditable.value === false) {
                postForm.slug = slugify(postForm.name, config.slugifyOptions);
                slugValueTeamName.value = slugify(
                    postForm.name,
                    config.slugifyOptions
                );
            }
        },
        { immediate: true }
    );
};

watch(
    () => isSlugEditable.value,
    (newValue) => {
        watchSlugInputChanges();
    },
    { immediate: true }
);

const handleCloseLock = function () {
    isSlugEditable.value = false;
};
const handleOpenLock = function () {
    isSlugEditable.value = true;
};

const updateTeam = () => {
    postForm.put(route("teams.update", props.team), {
        preserveScroll: true,
        onSuccess: (log) => {},
        onError: (err) => {},
        onFinish: () => {},
    });
};

const showErrorNotifications = ref(false);

const notificationsModalButton = function () {
    showErrorNotifications.value = false;
};

const getCurrentImage = computed(() => {
    return store.getters["mediaLibrary/getCurrentImage"];
});

// use media library
const showMediaLibraryModal = ref(false);
const showMediaLibraryModalLogo = ref(false);
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

const handleUploadLogo = function () {
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
        if (!Array.isArray(postForm.logo)) {
            postForm.logo = [];
        }

        const idExists = postForm.logo.some((item) => {
            return (
                item.id === getCurrentImage.value.currentImage.mediaLibrary.id
            );
        });

        if (idExists === false && Array.isArray(postForm.logo)) {
            postForm.logo.unshift(
                getCurrentImage.value.currentImage.mediaLibrary
            );
        }

        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    // end modal
};
const removePrimaryLogo = function (imageId) {
    postForm.logo = postForm.logo.map((image) => {
        return {
            ...image,
            pivot: {
                ...image.pivot,
                primary: image.id === imageId ? false : image?.pivot?.primary,
            },
        };
    });
};

const setAsPrimaryLogo = function (imageId) {
    postForm.logo = postForm.logo.map((image) => {
        if (image.id === imageId) {
            return {
                ...image,
                pivot: {
                    ...image.pivot,
                    primary: true,
                },
            };
        } else {
            return {
                ...image,
                pivot: {
                    ...image.pivot,
                    primary: false,
                },
            };
        }
    });
};
const handleRemoveLogo = function (imageId) {
    postForm.logo = postForm.logo.filter((image) => image.id !== imageId);
};

const handleUploadCoverImage = function () {
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
        if (!Array.isArray(postForm.cover_image)) {
            postForm.cover_image = [];
        }

        const idExists = postForm.cover_image?.some((item) => {
            return (
                item.id === getCurrentImage.value.currentImage.mediaLibrary.id
            );
        });

        if (idExists === false && Array.isArray(postForm.cover_image)) {
            postForm.cover_image.unshift(
                getCurrentImage.value.currentImage.mediaLibrary
            );
        }

        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    // end modal
};
const removePrimaryImage = function (imageId) {
    postForm.cover_image = postForm.cover_image.map((image) => {
        return {
            ...image,
            pivot: {
                ...image.pivot,
                primary: image.id === imageId ? false : image?.pivot?.primary,
            },
        };
    });
};

const setAsPrimaryImage = function (imageId) {
    postForm.cover_image = postForm.cover_image.map((image) => {
        if (image.id === imageId) {
            return {
                ...image,
                pivot: {
                    ...image.pivot,
                    primary: true,
                },
            };
        } else {
            return {
                ...image,
                pivot: {
                    ...image.pivot,
                    primary: false,
                },
            };
        }
    });
};
const handleRemoveCoverImage = function (imageId) {
    postForm.cover_image = postForm.cover_image.filter(
        (image) => image.id !== imageId
    );
};

onBeforeMount(() => {
    // User is editing an existing Resource, rather than creating a new one from scratch.
    if (props.team) {
        postForm.name = props.team.name;

        // slug logic
        // slug is editable when editing an existing post
        isSlugEditable.value = true;
        slugValueCustom.value = props.team.slug;
        slugValueTeamName.value = props.team.slug;

        postForm.public = props.team.public ? true : false;

        postForm.cover_image = props.team.coverImagesWithLogos.cover_images;
        postForm.logo = props.team.coverImagesWithLogos.logos;
    }
});
</script>

<template>
    <FormSection @submitted="handleUpdateTeam" :sidebarArea="true">
        <template #title>
            {{ $page.props.team && $page.props.team.name }}
        </template>

        <template #description> Update Company. </template>

        <template #main>
            <!-- Company Owner Information -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Company name
                    </div>
                </div>
                <div class="myInputGroup">
                    <InputLabel for="name" value="Update Company Name" />
                    <TextInput
                        placeholder="Slug.."
                        id="name"
                        v-model="postForm.name"
                        type="text"
                    />
                    <InputError :message="postForm.errors.name" />
                </div>
            </div>
            <!-- Company Owner Information -->
            <SectionBorder></SectionBorder>

            <!-- post slug start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Company slug or username
                    </div>
                </div>

                <div class="myInputGroup">
                    <div v-show="isSlugEditable === false">
                        <InputLabel for="slug" value="Slug or username" />
                        <div class="relative flex items-center">
                            <TextInput
                                placeholder="Slug.."
                                id="slug"
                                v-model="slugValueTeamName"
                                type="text"
                                class="block w-full myPrimaryInputReadonly"
                                readonly
                                autocomplete="off"
                            />
                            <div
                                @click="handleOpenLock"
                                class="cursor-pointer absolute inset-y-0 right-0 pr-1.5 flex items-center"
                            >
                                <LockClosedIcon
                                    class="w-5 h-5 text-myPrimaryErrorColor"
                                >
                                </LockClosedIcon>
                            </div>
                        </div>
                    </div>
                    <div v-show="isSlugEditable === true">
                        <InputLabel for="slug" value="Slug" />
                        <div class="relative flex items-center cursor-pointer">
                            <TextInput
                                placeholder="Slug.."
                                id="slug"
                                v-model="slugValueCustom"
                                type="text"
                                class="block w-full"
                                autocomplete="off"
                            />
                            <div
                                @click="handleCloseLock"
                                class="cursor-pointer absolute inset-y-0 right-0 pr-1.5 flex items-center"
                            >
                                <LockOpenIcon
                                    class="w-5 h-5 text-myPrimaryLinkColor"
                                >
                                </LockOpenIcon>
                            </div>
                        </div>
                        <div class="myPrimaryTag italic">
                            Slug: {{ postForm.slug }}
                        </div>
                    </div>
                    <InputError :message="postForm.errors.slug" />
                </div>
            </div>
            <!-- post slug end -->
        </template>
        <template #sidebar>
            <!-- post status - start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Status</div>
                    <p class="myPrimaryParagraph">
                        {{
                            postForm.public
                                ? "Public and accessible for public viewing."
                                : "Private and not accessible for public viewing."
                        }}
                    </p>
                </div>
                <div
                    class="myInputGroup flex myPrimaryGap flex-row-reverse justify-end"
                >
                    <InputLabel
                        :value="postForm.public ? 'Public' : 'Private'"
                        :class="{
                            'text-myPrimaryLinkColor': postForm.public,
                            'text-myPrimaryErrorColor': !postForm.public,
                        }"
                    />
                    <Switch
                        v-model="postForm.public"
                        :class="[
                            postForm.public
                                ? 'bg-myPrimaryLinkColor'
                                : 'bg-gray-200',
                            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-myPrimaryLinkColor focus:ring-offset-2',
                        ]"
                    >
                        <span class="sr-only">Use setting</span>
                        <span
                            :class="[
                                postForm.public
                                    ? 'translate-x-5'
                                    : 'translate-x-0',
                                'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                            ]"
                        >
                            <span
                                :class="[
                                    postForm.public
                                        ? 'opacity-0 ease-out duration-100'
                                        : 'opacity-100 ease-in duration-200',
                                    'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
                                ]"
                                aria-hidden="true"
                            >
                                <svg
                                    class="h-3 w-3 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 12 12"
                                >
                                    <path
                                        d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </span>
                            <span
                                :class="[
                                    postForm.public
                                        ? 'opacity-100 ease-in duration-200'
                                        : 'opacity-0 ease-out duration-100',
                                    'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
                                ]"
                                aria-hidden="true"
                            >
                                <svg
                                    class="h-3 w-3 text-myPrimaryLinkColor"
                                    fill="currentColor"
                                    viewBox="0 0 12 12"
                                >
                                    <path
                                        d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"
                                    />
                                </svg>
                            </span>
                        </span>
                    </Switch>
                </div>
                <InputError :message="postForm.errors.public" />
            </div>
            <!-- post status # end -->
            <!-- logos # start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Logo</div>
                </div>
                <!-- select - start -->
                <div @click="handleUploadLogo" class="myPrimaryFakeSelect">
                    <div class="relative flex items-center w-full py-0 p-0">
                        <span>
                            {{
                                postForm.logo && postForm.logo?.length === 0
                                    ? "Select Cover image"
                                    : "Add Additional Cover Images"
                            }}
                        </span>
                    </div>
                    <div
                        class="border-none rounded flex items-center justify-center h-full w-8"
                    >
                        <span class="material-symbols-outlined">
                            unfold_more
                        </span>
                    </div>
                </div>
                <!-- select - end -->

                <div
                    v-if="postForm.logo && postForm.logo?.length === 0"
                    class="space-y-6 mt-2"
                >
                    <p class="myPrimaryParagraph">No items selected.</p>
                </div>

                <div>
                    <p
                        v-if="postForm.logo && postForm.logo?.length !== 0"
                        class="py-4"
                    >
                        Added
                        {{ postForm.logo && postForm.logo?.length }}
                        {{
                            postForm.logo && postForm.logo?.length === 1
                                ? "Item"
                                : "Items"
                        }}
                    </p>
                    <div
                        v-if="
                            postForm.logo &&
                            Array.isArray(postForm?.logo) &&
                            postForm.logo?.length !== 0
                        "
                        class="p-2 border border-myPrimaryLightGrayColor"
                    >
                        <div
                            class="min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll divide-y divide-gray-200 pr-2"
                        >
                            <div
                                v-for="image in postForm.logo !== null &&
                                postForm.logo"
                                :key="image?.id"
                            >
                                <div
                                    class="flex justify-between items-center my-2 gap-4 myPrimaryTag w-max"
                                >
                                    <div
                                        class="flex justify-left items-center gap-2"
                                    >
                                        <div class="flex-shrink-0">
                                            <img
                                                @click="handleUploadLogo"
                                                :src="`/storage/uploads/${image?.thumbnail_path}`"
                                                alt="image"
                                                class="myPrimarythumbnailInsertPreview"
                                            />
                                        </div>

                                        <button
                                            class="myPrimaryTag bg-myPrimaryLinkColor text-white break-keep"
                                            v-if="
                                                image?.pivot?.primary &&
                                                postForm.logo.length > 1
                                            "
                                            type="button"
                                            @click="
                                                removePrimaryLogo(image?.id)
                                            "
                                        >
                                            <div
                                                class="flex items-center justify-center gap-2"
                                            >
                                                <span> Primary </span>
                                                <span
                                                    class="myMediumIcon material-symbols-outlined"
                                                >
                                                    check
                                                </span>
                                            </div>
                                        </button>
                                        <button
                                            class="myPrimaryTag transition bg-white break-keep"
                                            v-if="
                                                !image?.pivot?.primary &&
                                                postForm.logo?.length > 1
                                            "
                                            type="button"
                                            @click="setAsPrimaryLogo(image?.id)"
                                        >
                                            <span> Set as Primary </span>
                                        </button>
                                    </div>

                                    <button
                                        type="button"
                                        @click="handleRemoveLogo(image?.id)"
                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                    >
                                        <span
                                            class="myMediumIcon material-symbols-outlined"
                                        >
                                            delete
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="postForm.logo && postForm.logo?.length >= 1"
                            class="flex items-center justify-between border-t border-gray-200 pt-2 mt-1"
                        >
                            <p
                                @click="handleUploadLogo"
                                class="myPrimaryParagraph text-xs cursor-pointer font-medium"
                            >
                                Add Additional Images
                            </p>
                            <button
                                type="button"
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                @click="handleUploadLogo"
                            >
                                <span
                                    class="myMediumIcon material-symbols-outlined"
                                >
                                    add
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <InputError :message="postForm.errors.logo" />
            </div>
            <!-- logo image - end -->

            <!-- cover image # start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Cover image
                    </div>
                </div>
                <!-- select - start -->
                <div
                    @click="handleUploadCoverImage"
                    class="myPrimaryFakeSelect"
                >
                    <div class="relative flex items-center w-full py-0 p-0">
                        <span>
                            {{
                                postForm.cover_image &&
                                postForm.cover_image?.length === 0
                                    ? "Select Cover image"
                                    : "Add Additional Cover Images"
                            }}
                        </span>
                    </div>
                    <div
                        class="border-none rounded flex items-center justify-center h-full w-8"
                    >
                        <span class="material-symbols-outlined">
                            unfold_more
                        </span>
                    </div>
                </div>
                <!-- select - end -->

                <div
                    v-if="
                        postForm.cover_image &&
                        postForm.cover_image?.length === 0
                    "
                    class="space-y-6 mt-2"
                >
                    <p class="myPrimaryParagraph">No items selected.</p>
                </div>

                <div>
                    <p
                        v-if="
                            postForm.cover_image &&
                            postForm.cover_image?.length !== 0
                        "
                        class="py-4"
                    >
                        Added
                        {{
                            postForm.cover_image && postForm.cover_image?.length
                        }}
                        {{
                            postForm.cover_image &&
                            postForm.cover_image?.length === 1
                                ? "Item"
                                : "Items"
                        }}
                    </p>
                    <div
                        v-if="
                            postForm.cover_image &&
                            Array.isArray(postForm?.cover_image) &&
                            postForm.cover_image?.length !== 0
                        "
                        class="p-2 border border-myPrimaryLightGrayColor"
                    >
                        <div
                            class="min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll divide-y divide-gray-200 pr-2"
                        >
                            <div
                                v-for="image in postForm.cover_image !== null &&
                                postForm.cover_image"
                                :key="image?.id"
                            >
                                <div
                                    class="flex justify-between items-center my-2 gap-4 myPrimaryTag w-max"
                                >
                                    <div
                                        class="flex justify-left items-center gap-2"
                                    >
                                        <div class="flex-shrink-0">
                                            <img
                                                @click="handleUploadCoverImage"
                                                :src="`/storage/uploads/${image?.thumbnail_path}`"
                                                alt="image"
                                                class="myPrimarythumbnailInsertPreview"
                                            />
                                        </div>

                                        <button
                                            class="myPrimaryTag bg-myPrimaryLinkColor text-white"
                                            v-if="
                                                image?.pivot?.primary &&
                                                postForm.cover_image?.length > 1
                                            "
                                            type="button"
                                            @click="
                                                removePrimaryImage(image?.id)
                                            "
                                        >
                                            <div
                                                class="flex items-center justify-center gap-2"
                                            >
                                                <span> Primary </span>
                                                <span
                                                    class="myMediumIcon material-symbols-outlined"
                                                >
                                                    check
                                                </span>
                                            </div>
                                        </button>
                                        <button
                                            class="myPrimaryTag transition bg-white"
                                            v-if="
                                                !image?.pivot?.primary &&
                                                postForm.cover_image?.length > 1
                                            "
                                            type="button"
                                            @click="
                                                setAsPrimaryImage(image?.id)
                                            "
                                        >
                                            <span> Set as Primary </span>
                                        </button>
                                    </div>

                                    <button
                                        type="button"
                                        @click="
                                            handleRemoveCoverImage(image?.id)
                                        "
                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                    >
                                        <span
                                            class="myMediumIcon material-symbols-outlined"
                                        >
                                            delete
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="
                                postForm.cover_image &&
                                postForm.cover_image?.length >= 1
                            "
                            class="flex items-center justify-between border-t border-gray-200 pt-2 mt-1"
                        >
                            <p
                                @click="handleUploadCoverImage"
                                class="myPrimaryParagraph text-xs cursor-pointer font-medium"
                            >
                                Add Additional Images
                            </p>
                            <button
                                type="button"
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                @click="handleUploadCoverImage"
                            >
                                <span
                                    class="myMediumIcon material-symbols-outlined"
                                >
                                    add
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <InputError :message="postForm.errors.cover_image" />
            </div>
            <!-- cover image # end -->

            <MediaLibraryModal
                :forUserNotTeam="false"
                :user="$page.props.user"
                :team="team"
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
        </template>

        <template #actions>
            <SubmitButton :disabled="postForm.processing" buttonText="Update">
            </SubmitButton>
            <div
                class="flex justify-end mt-4"
                v-if="Object.values(postForm.errors).length !== 0"
            >
                <div
                    @click="showErrorNotifications = true"
                    class="w-fit py-1 flex items-center gap-2 rounded-md px-2 cursor-pointer italic"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4 text-myPrimaryErrorColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                    <p
                        class="myPrimaryParagraph text-xs text-myPrimaryErrorColor py-0 my-0"
                    >
                        Show
                        {{ Object.values(postForm.errors).length }}
                        {{
                            Object.values(postForm.errors).length === 1
                                ? "error"
                                : "errors"
                        }}
                    </p>
                </div>
            </div>

            <ActionMessage :on="postForm.recentlySuccessful" type="success">
                Successfully updated your team.
            </ActionMessage>

            <NotificationsFixedBottom
                :listOfMessages="Object.values(postForm.errors)"
                :show="showErrorNotifications"
                @notificationsModalButton="notificationsModalButton"
            >
                <div class="flex items-center justify-start gap-2">
                    <p class="myPrimaryParagraphError">
                        {{ Object.values(postForm.errors).length }}
                        {{
                            Object.values(postForm.errors).length === 1
                                ? "error"
                                : "errors"
                        }}
                    </p>
                </div>
            </NotificationsFixedBottom>
        </template>
    </FormSection>
</template>
