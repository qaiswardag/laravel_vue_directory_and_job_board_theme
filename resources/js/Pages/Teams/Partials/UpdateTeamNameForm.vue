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
import { onBeforeMount, onMounted } from "vue";

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

const postForm = useForm({
    name: "",
    public: true,
    cover_image: [],
    logo: [],
});

const updateTeam = () => {
    postForm.put(route("teams.update", props.team), {
        // error bag validation
        errorBag: "updateTeam",
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
        if (Array.isArray(postForm.logo) === false) {
            postForm.logo === [];
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
                primary: image.id === imageId ? false : image.pivot.primary,
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

//
//
//
//
//
//
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
        if (Array.isArray(postForm.cover_image) === false) {
            postForm.cover_image === [];
        }

        const idExists = postForm.cover_image.some((item) => {
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
                primary: image.id === imageId ? false : image.pivot.primary,
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
    if (props.team !== null) {
        postForm.name = props.team.name;
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

        <template #description>
            <!-- Your Role in Team # start -->
            <div
                v-if="
                    $page.props.team.owner &&
                    $page.props.user &&
                    $page.props.team.owner.id !== $page.props.user.id
                "
                class="mb-6 py-6 px-6 relative group bg-white focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor"
            >
                <p class="myPrimaryParagraph py-2">You</p>
                <div
                    class="p-2 rounded-md min-h-[4rem] max-h-[18rem] flex flex-col w-full border border-myPrimaryLightGrayColor divide-y divide-gray-200"
                >
                    <div class="hover:bg-white px-2">
                        <div class="rounded">
                            <!-- start photo -->
                            <div class="flex items-center gap-2 my-4">
                                <div
                                    v-if="
                                        $page.props.user &&
                                        $page.props.user.profile_photo_path !==
                                            null
                                    "
                                    class="flex-shrink-0"
                                >
                                    <img
                                        class="object-cover h-12 w-12 rounded-full"
                                        :src="`/storage/${$page.props.user.profile_photo_path}`"
                                        alt="User Image
                                            
                                        "
                                    />
                                </div>

                                <div
                                    v-if="
                                        $page.props.user &&
                                        $page.props.user.profile_photo_path ===
                                            null
                                    "
                                    class="flex-shrink-0 h-12 w-12 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
                                >
                                    {{
                                        $page.props.user.first_name
                                            .charAt(0)
                                            .toUpperCase()
                                    }}
                                    {{
                                        $page.props.user.last_name
                                            .charAt(0)
                                            .toUpperCase()
                                    }}
                                </div>
                                <span
                                    class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                >
                                    <p class="font-medium">
                                        {{ $page.props.user.first_name }}
                                        {{ $page.props.user.last_name }}
                                    </p>
                                    <p
                                        v-if="
                                            $page.props.currentUserTeamRole
                                                ?.name
                                        "
                                    >
                                        Team Role:
                                        {{
                                            $page.props.currentUserTeamRole
                                                ?.name
                                        }}
                                    </p>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Your Role in Team - end -->
            <!-- Team owner box - start -->
            <div
                v-if="
                    ($page.props.team.owner &&
                        $page.props.user &&
                        $page.props.team.owner.id === $page.props.user.id) ||
                    $page.props.team.owner.id !== $page.props.user.id
                "
                class="mb-6 py-6 px-6 relative group bg-white focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor"
            >
                <p class="myPrimaryParagraph py-2">
                    {{
                        $page.props.team.owner.id === $page.props.user.id
                            ? "You are Team Owner"
                            : "Team Owner"
                    }}
                </p>
                <div
                    class="p-2 rounded-md min-h-[4rem] max-h-[18rem] flex flex-col w-full border border-myPrimaryLightGrayColor divide-y divide-gray-200"
                >
                    <div class="hover:bg-white px-2">
                        <div class="rounded">
                            <div class="rounded">
                                <!-- start photo -->
                                <div class="flex items-center gap-2 my-4">
                                    <div
                                        v-if="
                                            $page.props.team.owner
                                                .profile_photo_path !== null
                                        "
                                        class="flex-shrink-0"
                                    >
                                        <img
                                            class="object-cover h-12 w-12 rounded-full"
                                            :src="`/storage/${$page.props.team.owner.profile_photo_path}`"
                                            :alt="
                                                $page.props.team.owner
                                                    .first_name +
                                                $page.props.team.owner.last_name
                                            "
                                        />
                                    </div>

                                    <div
                                        v-if="
                                            $page.props.team.owner
                                                .profile_photo_path === null
                                        "
                                        class="flex-shrink-0 h-12 w-12 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
                                    >
                                        {{
                                            $page.props.team.owner.first_name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                        {{
                                            $page.props.team.owner.last_name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                    </div>
                                    <span
                                        class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                    >
                                        <p class="font-medium">
                                            {{
                                                $page.props.team.owner
                                                    .first_name
                                            }}
                                            {{
                                                $page.props.team.owner.last_name
                                            }}
                                        </p>
                                        <p>Team Role: Owner</p>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Team owner box # end -->
        </template>

        <template #main>
            <!-- Team Owner Information -->
            <div class="myInputsOrganization">
                <div class="myInputGroup">
                    <InputLabel for="name" value="Update Team Name" />
                    <TextInput id="name" v-model="postForm.name" type="text" />
                    <InputError :message="postForm.errors.name" />
                </div>
            </div>
        </template>
        <template #sidebar>
            <!-- post status - start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Status</div>
                    <p class="myPrimaryParagraph">Specify Team status.</p>
                </div>
                <div
                    class="myInputGroup flex myPrimaryGap flex-row-reverse justify-end"
                >
                    <InputLabel
                        :value="postForm.public ? 'Published' : 'Unpublished'"
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
                    <p class="myPrimaryParagraph">Sit amet, adipiscing elit.</p>
                </div>
                <!-- select - start -->
                <div @click="handleUploadLogo" class="myPrimaryFakeSelect">
                    <div class="relative flex items-center w-full py-0 p-0">
                        <p class="myPrimaryParagraph">
                            {{
                                postForm.logo && postForm.logo?.length === 0
                                    ? "Select Cover image"
                                    : "Add Additional Cover Images"
                            }}
                        </p>
                    </div>
                    <div
                        class="border-none rounded flex items-center justify-center h-full w-8"
                    >
                        <ChevronUpDownIcon class="w-4 h-4"></ChevronUpDownIcon>
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
                        class="myPrimaryParagraph italic text-xs py-4"
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
                                    class="flex justify-between items-center rounded my-2 gap-4 text-xs font-medium"
                                >
                                    <div
                                        class="flex justify-left items-center gap-2"
                                    >
                                        <img
                                            @click="handleUploadLogo"
                                            :src="`/storage/uploads/${image?.medium_path}`"
                                            alt="image"
                                            class="myPrimarythumbnailInsertPreview"
                                        />

                                        <button
                                            class="myPrimaryTag bg-myPrimaryLinkColor text-white"
                                            v-if="image?.pivot?.primary"
                                            type="button"
                                            @click="
                                                removePrimaryLogo(image?.id)
                                            "
                                        >
                                            <div
                                                class="flex items-center justify-center gap-2"
                                            >
                                                <span> Primary </span>
                                                <CheckIcon
                                                    class="w-3 h-3 stroke-2"
                                                ></CheckIcon>
                                            </div>
                                        </button>
                                        <button
                                            class="myPrimaryTag transition"
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
                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                    >
                                        <TrashIcon
                                            class="shrink-0 w-4 h-4 m-2 stroke-2"
                                        ></TrashIcon>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="postForm.logo && postForm.logo?.length >= 1"
                            class="flex items-center justify-between border-t border-gray-200 pt-2 overflow-y-scroll"
                        >
                            <p
                                @click="handleUploadLogo"
                                class="myPrimaryParagraph text-xs italic cursor-pointer"
                            >
                                Add Additional Images
                            </p>
                            <button
                                type="button"
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                                @click="handleUploadLogo"
                            >
                                <PlusIcon
                                    class="shrink-0 w-4 h-4 m-2 stroke-2"
                                ></PlusIcon>
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
                    <p class="myPrimaryParagraph">Sit amet, adipiscing elit.</p>
                </div>
                <!-- select - start -->
                <div
                    @click="handleUploadCoverImage"
                    class="myPrimaryFakeSelect"
                >
                    <div class="relative flex items-center w-full py-0 p-0">
                        <p class="myPrimaryParagraph">
                            {{
                                postForm.cover_image &&
                                postForm.cover_image?.length === 0
                                    ? "Select Cover image"
                                    : "Add Additional Cover Images"
                            }}
                        </p>
                    </div>
                    <div
                        class="border-none rounded flex items-center justify-center h-full w-8"
                    >
                        <ChevronUpDownIcon class="w-4 h-4"></ChevronUpDownIcon>
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
                        class="myPrimaryParagraph italic text-xs py-4"
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
                                    class="flex justify-between items-center rounded my-2 gap-4 text-xs font-medium"
                                >
                                    <div
                                        class="flex justify-left items-center gap-2"
                                    >
                                        <img
                                            @click="handleUploadCoverImage"
                                            :src="`/storage/uploads/${image?.medium_path}`"
                                            alt="image"
                                            class="myPrimarythumbnailInsertPreview"
                                        />

                                        <button
                                            class="myPrimaryTag bg-myPrimaryLinkColor text-white"
                                            v-if="image?.pivot?.primary"
                                            type="button"
                                            @click="
                                                removePrimaryImage(image?.id)
                                            "
                                        >
                                            <div
                                                class="flex items-center justify-center gap-2"
                                            >
                                                <span> Primary </span>
                                                <CheckIcon
                                                    class="w-3 h-3 stroke-2"
                                                ></CheckIcon>
                                            </div>
                                        </button>
                                        <button
                                            class="myPrimaryTag transition"
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
                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                    >
                                        <TrashIcon
                                            class="shrink-0 w-4 h-4 m-2 stroke-2"
                                        ></TrashIcon>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="
                                postForm.cover_image &&
                                postForm.cover_image?.length >= 1
                            "
                            class="flex items-center justify-between border-t border-gray-200 pt-2 overflow-y-scroll"
                        >
                            <p
                                @click="handleUploadCoverImage"
                                class="myPrimaryParagraph text-xs italic cursor-pointer"
                            >
                                Add Additional Images
                            </p>
                            <button
                                type="button"
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                                @click="handleUploadCoverImage"
                            >
                                <PlusIcon
                                    class="shrink-0 w-4 h-4 m-2 stroke-2"
                                ></PlusIcon>
                            </button>
                        </div>
                    </div>
                </div>

                <InputError :message="postForm.errors.cover_image" />
            </div>
            <!-- cover image # end -->

            <MediaLibraryModal
                v-if="showMediaLibraryModal === true"
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
                        errors
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
                        errors
                    </p>
                </div>
            </NotificationsFixedBottom>
        </template>
    </FormSection>
</template>
