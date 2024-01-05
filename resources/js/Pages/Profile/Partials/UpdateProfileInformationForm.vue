<script setup>
import { ref, watch, computed, onMounted, nextTick, onBeforeMount } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import SecondaryButton from "@/Components/Buttons/SecondaryButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { Switch } from "@headlessui/vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";
import countryListAllIsoData from "@/utils/country-list-all-iso-data";
import NotificationsFixedBottom from "@/Components/Modals/NotificationsFixedBottom.vue";
import MediaLibraryModal from "@/Components/Modals/MediaLibraryModal.vue";
import { useStore } from "vuex";
import { delay } from "@/helpers/delay";
import PageBuilderModal from "@/Components/Modals/PageBuilderModal.vue";
import PageBuilderView from "@/Pages/PageBuilder/PageBuilder.vue";
import PageBuilder from "@/composables/PageBuilder";

import {
    TrashIcon,
    CheckIcon,
    NewspaperIcon,
    XMarkIcon,
    ChevronUpDownIcon,
    PlusIcon,
} from "@heroicons/vue/24/outline";
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from "@headlessui/vue";

const props = defineProps({
    user: Object,
});

// store
const store = useStore();

const showUserInformation = ref(false);

const form = useForm({
    _method: "PUT",
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    username: props.user.username,
    email: props.user.email,
    public: props.user.public ? true : false,

    country: null,
    city: props.user.city,
    state: props.user.state,
    line1: props.user.line1,
    line2: props.user.line2,
    postal_code: props.user.postal_code,
    phone_code: props.user.phone_code,
    phone: props.user.phone,
    job_title: props.user.job_title,

    content: "",
    user_image: [],
});

//
//
//
//
//
// page builder logic start
const pageBuilder = new PageBuilder(store);

// Builder # Start
const getComponents = computed(() => {
    return store.getters["pageBuilderState/getComponents"];
});

const openDesignerModal = ref(false);

// use designer model
const firstDesignerModalButtonFunction = ref(null);
const secondDesignerModalButtonFunction = ref(null);

const handleDraftForUpdate = async function () {
    store.commit("user/setIsLoading", true);

    await nextTick();
    pageBuilder.areComponentsStoredInLocalStorageUpdate();

    await nextTick();
    pageBuilder.setEventListenersForElements();

    await delay();
    store.commit("user/setIsLoading", false);
};

const handlePageBuilder = async function () {
    // set modal standards
    store.commit("user/setIsLoading", true);

    await delay();
    await nextTick();
    openDesignerModal.value = true;

    // handle click
    firstDesignerModalButtonFunction.value = async function () {
        store.commit("user/setIsLoading", true);

        await nextTick();
        pageBuilder.saveComponentsLocalStorageUpdate();
        pageBuilder.synchronizeDOMAndComponents();
        await delay();

        // set open modal
        openDesignerModal.value = false;
        store.commit("user/setIsLoading", false);
    };

    // handle click
    secondDesignerModalButtonFunction.value = async function () {
        store.commit("user/setIsLoading", true);

        await nextTick();
        pageBuilder.saveComponentsLocalStorageUpdate();
        pageBuilder.synchronizeDOMAndComponents();

        await nextTick();
        form.content =
            Array.isArray(getComponents.value) &&
            getComponents.value
                .map((component) => {
                    return component.html_code;
                })
                .join("");

        // set open modal

        openDesignerModal.value = false;
        await delay();
        store.commit("user/setIsLoading", false);
    };

    store.commit("user/setIsLoading", false);

    // end modal
};
// Builder # End
// page builder logic end
//
//
//
//
//
// Media library for profile photo # start

const getCurrentImage = computed(() => {
    return store.getters["mediaLibrary/getCurrentImage"];
});
//
//
//
//
const showMediaLibraryUserModal = ref(false);
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
//
//
const handleUploadUserImage = function () {
    // handle show media library modal
    showMediaLibraryUserModal.value = true;

    // set media library modal standards
    titleMedia.value = "Media Library";
    descriptionMedia.value = null;
    firstButtonMedia.value = "Close";
    secondButtonMedia.value = "Select image";
    thirdButtonMedia.value = null;
    // handle click
    firstMediaButtonFunction.value = function () {
        // handle show media library modal
        showMediaLibraryUserModal.value = false;
    };
    //
    // handle click
    secondMediaButtonFunction.value = function () {
        if (Array.isArray(form.user_image) === false) {
            form.user_image = [];
        }

        const idExists = form.user_image?.some((item) => {
            return (
                item.id === getCurrentImage.value.currentImage.mediaLibrary.id
            );
        });

        if (idExists === false && Array.isArray(form.user_image)) {
            form.user_image.unshift(
                getCurrentImage.value.currentImage.mediaLibrary
            );
        }

        // handle show media library modal
        showMediaLibraryUserModal.value = false;
    };
    // end modal
};
//
const removePrimaryImage = function (imageId) {
    form.user_image = form.user_image.map((image) => {
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
    form.user_image = form.user_image.map((image) => {
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

const handleRemoveUserImage = function (imageId) {
    form.user_image = form.user_image.filter((image) => image.id !== imageId);
};
// Media library for profile photo # end
//
//
//
//
//
//
//

const verificationLinkSent = ref(null);

const clearPageBuilderOnSuccessUpdate = function () {
    pageBuilder.removeItemComponentsLocalStorageUpdate();
    store.commit("pageBuilderState/setComponents", []);
};

const updateProfileInformation = () => {
    form.country = selectedCountry.value?.code;
    form.phone_code = selectedPhoneCode.value?.phone_code;

    form.post(route("user-profile-information.update"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
        onSuccess: () => {
            clearPageBuilderOnSuccessUpdate();
        },
        onError: (err) => {},
        onFinish: () => {},
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const showErrorNotifications = ref(false);

const notificationsModalButton = function () {
    showErrorNotifications.value = false;
};

// country # start
const selectedCountry = ref(null);
const query = ref("");

const filteredCountries = computed(() =>
    query.value === ""
        ? countryListAllIsoData
        : countryListAllIsoData.filter(
              (country) =>
                  country.country !== null &&
                  country.country
                      ?.toLowerCase()
                      .includes(query.value.toLowerCase())
          )
);

// country # end

// phone country code # start
const selectedPhoneCode = ref(null);
const queryPhoneCode = ref("");

const filteredPhoneCodes = computed(() =>
    queryPhoneCode.value === ""
        ? countryListAllIsoData
        : countryListAllIsoData.filter((country) => {
              return (
                  country.country !== null &&
                  (country.phone_code.includes(queryPhoneCode.value) ||
                      country.country
                          ?.toLowerCase()
                          .includes(queryPhoneCode.value.toLowerCase()))
              );
          })
);

// phone country code # end

const pathPageBuilderLocalStorageUpdateDraft = ref(
    `page-builder-draft-user-${props.user.id}`
);

onBeforeMount(() => {
    if (props.user && !props.user.content) {
        store.commit("pageBuilderState/setComponents", []);
    }

    // User is editing an existing Resource, rather than creating a new one from scratch.
    if (props.user && props.user.content) {
        // Parse the HTML content using DOMParser
        const parser = new DOMParser();
        const doc = parser.parseFromString(props.user.content, "text/html");

        // Select all <section> elements with data-componentid attribute
        const sectionElements = doc.querySelectorAll(
            "section[data-componentid]"
        );

        const extractedSections = [];
        // Loop through the selected elements and extract outerHTML
        sectionElements.forEach((section) => {
            extractedSections.push({
                html_code: section.outerHTML,
                id: section.dataset.componentid,
            });
        });

        store.commit("pageBuilderState/setComponents", extractedSections);

        form.content = props.user.content;
    }

    store.commit(
        "pageBuilderState/setLocalStorageItemNameUpdate",
        pathPageBuilderLocalStorageUpdateDraft.value
    );
});

//
//
onMounted(() => {
    if (props.user && props.user.country) {
        selectedCountry.value =
            filteredCountries.value.find((country) => {
                return country.code === props.user.country;
            }) || null;
    }
    if (props.user && props.user.phone_code) {
        selectedPhoneCode.value =
            filteredPhoneCodes.value.find((country) => {
                return country.phone_code === props.user.phone_code;
            }) || null;
    }

    if (props.user && props.user.user_photo) {
        form.user_image = props.user.user_photo;
    }
});
</script>

<template>
    <PageBuilderModal
        :show="openDesignerModal"
        updateOrCreate="update"
        @firstDesignerModalButtonFunction="firstDesignerModalButtonFunction"
        @secondDesignerModalButtonFunction="secondDesignerModalButtonFunction"
        @handleDraftForUpdate="handleDraftForUpdate"
    >
        <PageBuilderView :forUserNotTeam="true" :user="user"></PageBuilderView>
    </PageBuilderModal>

    <MediaLibraryModal
        :forUserNotTeam="true"
        :user="user"
        :open="showMediaLibraryUserModal"
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

    <FormSection @submitted="updateProfileInformation">
        <template #title> Profile Details </template>

        <template #description>
            Update your account's profile information.
        </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Username</div>
                </div>
                <div class="myInputGroup">
                    <InputLabel for="user_name" value="Username" />
                    <div class="relative flex items-center">
                        <TextInput
                            id="user_name"
                            v-model="form.username"
                            :value="user.username"
                            type="text"
                            autocomplete="off"
                            readonly
                            class="myPrimaryInputReadonly"
                        />
                        <div
                            class="absolute inset-y-0 right-0 pr-1.5 flex items-center cursor-pointer"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-5 h-5 text-myPrimaryErrorColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                                />
                            </svg>
                        </div>
                    </div>
                    <p class="myPrimaryParagraph text-xs italic">
                        Your username must be between 4-15 characters in length
                        and can only contain letters (A-Z) numbers (0-9) and
                        underscores. Special characters and spaces are not
                        allowed.
                    </p>
                    <InputError :message="form.errors.username" />
                </div>

                <SectionBorder></SectionBorder>

                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Profile details
                    </div>
                </div>

                <div class="md:flex items-center justify-center myPrimaryGap">
                    <div class="myInputGroup">
                        <InputLabel for="first_name" value="First name" />
                        <TextInput
                            id="first_name"
                            v-model="form.first_name"
                            type="text"
                            placeholder="First name.."
                            autocomplete="first_name"
                        />
                        <InputError :message="form.errors.first_name" />
                    </div>

                    <div class="myInputGroup">
                        <InputLabel for="last_name" value="Last name" />
                        <TextInput
                            id="last_name"
                            v-model="form.last_name"
                            type="text"
                            placeholder="Last name.."
                            autocomplete="last_name"
                        />
                        <InputError :message="form.errors.last_name" />
                    </div>
                </div>

                <!-- Email -->
                <div class="myInputGroup">
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        type="email"
                        v-model="form.email"
                        placeholder="Email.."
                        autocomplete="email"
                    />
                    <InputError :message="form.errors.email" />

                    <div
                        v-if="
                            $page.props.jetstream.hasEmailVerification &&
                            user.email_verified_at === null
                        "
                    >
                        <p class="text-sm mt-2">
                            Your email address is unverified.

                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="underline text-myPrimaryDarkGrayColor hover:text-myPrimaryDarkGrayColor"
                                @click.prevent="sendEmailVerification"
                            >
                                Click here to re-send the verification email.
                            </Link>
                        </p>

                        <div
                            v-show="verificationLinkSent"
                            class="mt-2 font-normal text-sm text-myPrimaryLinkColor"
                        >
                            A new verification link has been sent to your email
                            address.
                        </div>
                    </div>
                </div>

                <div class="myInputGroup">
                    <InputLabel for="job_title" value="Job title" />
                    <TextInput
                        v-model="form.job_title"
                        id="job_title"
                        type="text"
                        name="job_title"
                        placeholder="Job title.."
                        autocomplete="off"
                    />
                    <InputError :message="form.errors.job_title" />
                </div>
            </div>

            <!-- Builder #start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeader">Linkthree</div>
                <!-- Add Content # start -->
                <div
                    class="rounded-lg mt-4 border-2 border-dashed border-gray-300 p-8 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    <button type="button" @click="handlePageBuilder">
                        <span class="myMediumIcon material-symbols-outlined">
                            stacks
                        </span>
                    </button>

                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        Build your profile by adding Components
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Get started by adding components using the drag & drop
                        Page Builder.
                    </p>
                    <div class="mt-6">
                        <button
                            @click="handlePageBuilder"
                            type="button"
                            class="myPrimaryButton"
                        >
                            <span
                                class="myMediumIcon material-symbols-outlined"
                            >
                                stacks
                            </span>
                            Add content
                        </button>
                    </div>
                </div>
                <!-- Add Content # end -->
                <InputError :message="form.errors.content" />
            </div>
            <!-- Builder #end -->
        </template>
        <template #sidebar>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Status</div>
                    <p class="myPrimaryParagraph">
                        {{
                            form.public
                                ? "Public and accessible for public viewing."
                                : "Private and not accessible for public viewing."
                        }}
                    </p>
                </div>
                <div
                    class="myInputGroup flex myPrimaryGap flex-row-reverse justify-end"
                >
                    <InputLabel
                        :value="form.public ? 'Public' : 'Private'"
                        :class="{
                            'text-myPrimaryLinkColor': form.public,
                            'text-myPrimaryErrorColor': !form.public,
                        }"
                    />
                    <Switch
                        v-model="form.public"
                        :class="[
                            form.public
                                ? 'bg-myPrimaryLinkColor'
                                : 'bg-gray-200',
                            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-myPrimaryLinkColor focus:ring-offset-2',
                        ]"
                    >
                        <span class="sr-only">Use setting</span>
                        <span
                            :class="[
                                form.public ? 'translate-x-5' : 'translate-x-0',
                                'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                            ]"
                        >
                            <span
                                :class="[
                                    form.public
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
                                    form.public
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
                <InputError :message="form.errors.public" />
            </div>

            <!-- Profile photo # start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Profile photo
                    </div>
                </div>
                <!-- select - start -->
                <div @click="handleUploadUserImage" class="myPrimaryFakeSelect">
                    <div class="relative flex items-center w-full py-0 p-0">
                        <span>
                            {{
                                form.user_image && form.user_image?.length === 0
                                    ? "Select Profile photo"
                                    : "Update Profile Photo"
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
                    v-if="form.user_image && form.user_image?.length === 0"
                    class="space-y-6 mt-2"
                >
                    <p class="myPrimaryParagraph">No items selected.</p>
                </div>

                <div>
                    <p
                        v-if="form.user_image && form.user_image?.length !== 0"
                        class="py-4"
                    >
                        Added
                        {{ form.user_image && form.user_image?.length }}
                        {{
                            form.user_image && form.user_image?.length === 1
                                ? "Item"
                                : "Items"
                        }}
                    </p>
                    <div
                        v-if="
                            form.user_image &&
                            Array.isArray(form?.user_image) &&
                            form.user_image?.length !== 0
                        "
                        class="p-2 border border-myPrimaryLightGrayColor"
                    >
                        <div
                            class="min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll divide-y divide-gray-200 pr-2"
                        >
                            <div
                                v-for="image in form.user_image !== null &&
                                form.user_image"
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
                                                @click="handleUploadUserImage"
                                                :src="`/storage/uploads/${image?.thumbnail_path}`"
                                                alt="image"
                                                class="myPrimarythumbnailInsertPreview"
                                            />
                                        </div>

                                        <button
                                            class="myPrimaryTag bg-myPrimaryLinkColor text-white w-full break-keep"
                                            v-if="
                                                image?.pivot?.primary &&
                                                form.user_image.length > 1
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
                                            class="myPrimaryTag transition bg-white break-keep"
                                            v-if="
                                                !image?.pivot?.primary &&
                                                form.user_image?.length > 1
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
                                            handleRemoveUserImage(image?.id)
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
                                form.user_image && form.user_image?.length >= 1
                            "
                            class="flex items-center justify-between border-t border-gray-200 pt-2 mt-1"
                        >
                            <p
                                @click="handleUploadUserImage"
                                class="myPrimaryParagraph text-xs cursor-pointer font-medium"
                            >
                                Add Additional Images
                            </p>
                            <button
                                type="button"
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                @click="handleUploadUserImage"
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

                <InputError :message="form.errors.user_image" />
            </div>
            <!-- Profile photo # end -->

            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <button
                        type="button"
                        @click="showUserInformation = !showUserInformation"
                        class="w-full"
                    >
                        <div
                            class="myPrimaryFormOrganizationHeader flex w-full items-center justify-between pb-2"
                        >
                            <div>Information</div>

                            <div class="ml-auto flex items-center">
                                <span
                                    v-if="!showUserInformation"
                                    class="myMediumIcon material-symbols-outlined"
                                >
                                    add
                                </span>
                                <span
                                    v-if="showUserInformation"
                                    class="myMediumIcon material-symbols-outlined"
                                >
                                    remove
                                </span>
                            </div>
                        </div>
                    </button>
                    <p class="myPrimaryParagraph">
                        Additional non-public information.
                    </p>
                </div>

                <div
                    v-if="showUserInformation"
                    class="border-t border-gray-200 py-6"
                >
                    <div class="myInputGroup">
                        <InputLabel for="laaline1" value="Street address" />
                        <TextInput
                            v-model="form.line1"
                            placeholder="Street address.."
                            type="text"
                            id="laaline1"
                            name="laaline1"
                            autocomplete="laaline1"
                        />
                        <InputError :message="form.errors.line1" />
                    </div>

                    <div class="myInputGroup">
                        <InputLabel
                            for="laaline2"
                            value="Apt, suite, building etc."
                        />
                        <TextInput
                            v-model="form.line2"
                            placeholder="Apt, suite, building.."
                            type="text"
                            id="laaline2"
                            name="laaline2"
                            autocomplete="laaline2"
                        />
                        <InputError :message="form.errors.line2" />
                    </div>

                    <div class="myInputGroup">
                        <!-- Headless UI select # start -->
                        <InputLabel for="laacountry" value="Country" />
                        <!-- Headless UI select # start -->
                        <Combobox v-model="selectedCountry">
                            <div class="relative mt-1">
                                <div class="relative">
                                    <ComboboxInput
                                        name="laacountry"
                                        id="laacountry"
                                        autocomplete="laacountry"
                                        class="myPrimarySelect"
                                        placeholder="Search.."
                                        :displayValue="
                                            (country) => {
                                                return country?.country;
                                            }
                                        "
                                        @change="query = $event.target.value"
                                    />

                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-2"
                                    >
                                        <div
                                            class="flex items-center justify-center gap-2"
                                        >
                                            <ComboboxButton
                                                class="h-8 w-8 cursor-pointer rounded flex items-center justify-center"
                                            >
                                                <span
                                                    class="material-symbols-outlined"
                                                >
                                                    unfold_more
                                                </span>
                                            </ComboboxButton>
                                        </div>
                                    </div>
                                </div>
                                <TransitionRoot
                                    leave="transition ease-in duration-100"
                                    leaveFrom="opacity-100"
                                    leaveTo="opacity-0"
                                    @after-leave="query = ''"
                                >
                                    <ComboboxOptions
                                        class="absolute z-30 mt-1 max-h-36 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                    >
                                        <div
                                            v-if="
                                                filteredCountries.length ===
                                                    0 && query !== ''
                                            "
                                            class="relative cursor-default select-none py-2 px-4 text-gray-700"
                                        >
                                            Nothing found.
                                        </div>

                                        <ComboboxOption
                                            v-for="country in filteredCountries"
                                            as="template"
                                            :key="country.code"
                                            :value="country"
                                            v-slot="{ selected, active }"
                                        >
                                            <li
                                                class="relative cursor-default select-none py-2 pl-10 pr-4"
                                                :class="{
                                                    'bg-gray-800 text-white':
                                                        active,
                                                    'text-myPrimaryDarkGrayColor':
                                                        !active,
                                                }"
                                            >
                                                <span
                                                    class="block truncate"
                                                    :class="{
                                                        'font-medium': selected,
                                                        'font-normal':
                                                            !selected,
                                                    }"
                                                >
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        {{
                                                            country.country
                                                                ? country.country
                                                                : "None"
                                                        }}
                                                    </div>
                                                </span>

                                                <span
                                                    v-if="selected"
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                    :class="{
                                                        'text-white': active,
                                                        'text-gray-800':
                                                            !active,
                                                    }"
                                                >
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        check
                                                    </span>
                                                </span>
                                            </li>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </TransitionRoot>
                            </div>
                        </Combobox>
                        <InputError :message="form.errors.country" />
                    </div>
                    <div class="myInputGroup">
                        <InputLabel for="laacity" value="City" />
                        <TextInput
                            v-model="form.city"
                            type="text"
                            id="laacity"
                            name="laacity"
                            autocomplete="laacity"
                            placeholder="City.."
                        />
                        <InputError :message="form.errors.city" />
                    </div>
                    <div class="myInputGroup">
                        <InputLabel for="laastate" value="Province or region" />
                        <TextInput
                            v-model="form.state"
                            placeholder="Province or region.."
                            type="text"
                            id="laastate"
                            name="laastate"
                            autocomplete="laastate"
                        />
                        <InputError :message="form.errors.state" />
                    </div>
                    <div class="myInputGroup">
                        <InputLabel for="laapostal_code" value="Postal code " />
                        <TextInput
                            v-model="form.postal_code"
                            placeholder="Postal code.."
                            type="text"
                            id="laapostal_code"
                            name="laapostal_code"
                            autocomplete="laapostal_code"
                        />
                        <InputError :message="form.errors.postal_code" />
                    </div>

                    <!-- phone code start -->
                    <div class="myInputGroup">
                        <!-- Headless UI select # start -->
                        <InputLabel
                            for="laaphone_code"
                            value="Phone country code"
                        />
                        <!-- Headless UI select # start -->
                        <Combobox v-model="selectedPhoneCode">
                            <div class="relative mt-1">
                                <div class="relative">
                                    <ComboboxInput
                                        name="laaphone_code"
                                        id="laaphone_code"
                                        autocomplete="laaphone_code"
                                        class="myPrimarySelect"
                                        placeholder="Search.."
                                        :displayValue="
                                            (country) => {
                                                return country?.phone_code
                                                    ? `${country.phone_code} ${country?.country}`
                                                    : '';
                                            }
                                        "
                                        @change="
                                            queryPhoneCode = $event.target.value
                                        "
                                    />

                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-2"
                                    >
                                        <div
                                            class="flex items-center justify-center gap-2"
                                        >
                                            <ComboboxButton
                                                class="h-8 w-8 cursor-pointer rounded flex items-center justify-center"
                                            >
                                                <span
                                                    class="material-symbols-outlined"
                                                >
                                                    unfold_more
                                                </span>
                                            </ComboboxButton>
                                        </div>
                                    </div>
                                </div>
                                <TransitionRoot
                                    leave="transition ease-in duration-100"
                                    leaveFrom="opacity-100"
                                    leaveTo="opacity-0"
                                    @after-leave="queryPhoneCode = ''"
                                >
                                    <ComboboxOptions
                                        class="absolute z-30 mt-1 max-h-36 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                    >
                                        <div
                                            v-if="
                                                filteredPhoneCodes.length ===
                                                    0 && queryPhoneCode !== ''
                                            "
                                            class="relative cursor-default select-none py-2 px-4 text-gray-700"
                                        >
                                            Nothing found.
                                        </div>

                                        <ComboboxOption
                                            v-for="country in filteredPhoneCodes"
                                            as="template"
                                            :key="country.code"
                                            :value="country"
                                            v-slot="{ selected, active }"
                                        >
                                            <li
                                                class="relative cursor-default select-none py-2 pl-10 pr-4"
                                                :class="{
                                                    'bg-gray-800 text-white':
                                                        active,
                                                    'text-myPrimaryDarkGrayColor':
                                                        !active,
                                                }"
                                            >
                                                <span
                                                    class="block truncate"
                                                    :class="{
                                                        'font-medium': selected,
                                                        'font-normal':
                                                            !selected,
                                                    }"
                                                >
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        {{
                                                            country?.phone_code
                                                                ? country.phone_code
                                                                : "None"
                                                        }}
                                                    </div>
                                                </span>

                                                <span
                                                    v-if="selected"
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                    :class="{
                                                        'text-white': active,
                                                        'text-gray-800':
                                                            !active,
                                                    }"
                                                >
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        check
                                                    </span>
                                                </span>
                                            </li>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </TransitionRoot>
                            </div>
                        </Combobox>
                        <InputError :message="form.errors.phone_code" />
                    </div>
                    <!-- phone code end -->

                    <div class="myInputGroup">
                        <InputLabel for="laaphone" value="Phone" />
                        <TextInput
                            v-model="form.phone"
                            type="text"
                            id="laaphone"
                            name="laaphone"
                            autocomplete="laaphone"
                            placeholder="Phone.."
                        />
                        <InputError :message="form.errors.phone" />
                    </div>
                </div>
            </div>
        </template>
        <template #actions>
            <SubmitButton :disabled="form.processing" buttonText="Update">
            </SubmitButton>
            <div
                class="flex justify-end mt-4"
                v-if="Object.values(form.errors).length !== 0"
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
                        {{ Object.values(form.errors).length }}
                        {{
                            Object.values(form.errors).length === 1
                                ? "error"
                                : "errors"
                        }}
                    </p>
                </div>
            </div>
            <ActionMessage :on="form.recentlySuccessful" type="success">
                Successfully updated your Profile Information.
            </ActionMessage>
            <NotificationsFixedBottom
                :listOfMessages="Object.values(form.errors)"
                :show="showErrorNotifications"
                @notificationsModalButton="notificationsModalButton"
            >
                <div class="flex items-center justify-start gap-2">
                    <p class="myPrimaryParagraphError">
                        {{ Object.values(form.errors).length }}
                        {{
                            Object.values(form.errors).length === 1
                                ? "error"
                                : "errors"
                        }}
                    </p>
                </div>
            </NotificationsFixedBottom>
        </template>
    </FormSection>
</template>
