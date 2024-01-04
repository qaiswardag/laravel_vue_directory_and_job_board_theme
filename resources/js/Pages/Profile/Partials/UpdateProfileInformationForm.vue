<script setup>
import { ref, watch, computed, onMounted } from "vue";
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

    photo: null,
});

//
//
//
//
//
//
//
//
//
//
//
// Media library for profile photo # start
//
//
// store
const store = useStore();

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
// Media library for profile photo # end

//
//
//
//
//
const handleUploadCoverImage = function () {
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
        if (Array.isArray(form.cover_image) === false) {
            form.cover_image = [];
        }

        const idExists = form.cover_image?.some((item) => {
            return (
                item.id === getCurrentImage.value.currentImage.mediaLibrary.id
            );
        });

        if (idExists === false && Array.isArray(form.cover_image)) {
            form.cover_image.unshift(
                getCurrentImage.value.currentImage.mediaLibrary
            );
        }

        // handle show media library modal
        showMediaLibraryUserModal.value = false;
    };
    // end modal
};
//
//
//
//
//
//
//
//
//
const modalShowDeletePhoto = ref(false);

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

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const profilePhotoIsDirty = ref(false);

const updateProfileInformation = () => {
    form.country = selectedCountry.value?.code;
    form.phone_code = selectedPhoneCode.value?.phone_code;

    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }
    form.post(route("user-profile-information.update"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
        onSuccess: () => {
            clearPhotoFileInput();
            profilePhotoIsDirty.value = false;
        },
        onError: (err) => {},
        onFinish: () => {},
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route("current-user-photo.destroy"), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const handleDeletePhoto = function () {
    // handle show modal for unique content
    modalShowDeletePhoto.value = true;
    // set modal standards
    typeModal.value = "delete";
    gridColumnModal.value = 2;
    titleModal.value = "Delete Photo";
    descriptionModal.value = "Are you sure you want to delete your photo?";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete";
    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeletePhoto.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        deletePhoto();
        // handle show modal for unique content
        modalShowDeletePhoto.value = false;
    };
    // end modal
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
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

const handleRemoveInputPhoneCode = function () {
    selectedPhoneCode.value = null;
};
// phone country code # end

//
//
//

watch(photoPreview, (newValue) => {
    profilePhotoIsDirty.value = true;
});

onMounted(() => {
    if (props.user.country) {
        selectedCountry.value =
            filteredCountries.value.find((country) => {
                return country.code === props.user.country;
            }) || null;
    }
    if (props.user.phone_code) {
        selectedPhoneCode.value =
            filteredPhoneCodes.value.find((country) => {
                return country.phone_code === props.user.phone_code;
            }) || null;
    }
});
</script>

<template>
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
                <div class="md:flex items-center justify-center myPrimaryGap">
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
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Your Profile photo
                    </div>
                </div>
                <div class="myInputGroup">
                    <InputLabel for="email" value="Profile Image" />
                    <input
                        ref="photoInput"
                        type="file"
                        class="hidden"
                        @change="updatePhotoPreview"
                    />
                    <div
                        class="flex-col items-center gap-2 mb-8 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6"
                    >
                        <div
                            v-if="
                                !photoPreview &&
                                user.profile_photo_path !== null
                            "
                            @click.prevent="selectNewPhoto"
                            class="mt-2 cursor-pointer"
                        >
                            <img
                                class="object-cover h-16 w-16 rounded-full"
                                :src="`/storage/${user.profile_photo_path}`"
                                :alt="user.first_name + user.last_name"
                            />
                        </div>

                        <div v-if="photoPreview !== null">
                            <span
                                class="block rounded-full h-16 w-16 bg-cover bg-no-repeat bg-center"
                                :style="
                                    'background-image: url(\'' +
                                    photoPreview +
                                    '\');'
                                "
                            />
                        </div>

                        <div
                            v-if="
                                user.profile_photo_path === null &&
                                !photoPreview
                            "
                            @click.prevent="selectNewPhoto"
                            class="cursor-pointer h-16 w-16 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
                        >
                            {{ user.first_name.charAt(0).toUpperCase() }}
                            {{ user.last_name.charAt(0).toUpperCase() }}
                        </div>
                        <span
                            class="flex flex-col items-center gap-1 myPrimaryParagraph"
                        >
                            <span>
                                {{ user.first_name }}
                                {{ user.last_name }}
                            </span>
                            <span>
                                {{ $page.props.user.email }}
                            </span>
                        </span>
                    </div>

                    <div class="flex justify-center items-center myPrimaryGap">
                        <button
                            class="myPrimaryButton"
                            type="button"
                            @click.prevent="selectNewPhoto"
                        >
                            Upload New
                        </button>

                        <button
                            v-if="
                                photoPreview || user.profile_photo_path !== null
                            "
                            type="button"
                            class="myPrimaryDeleteButton"
                            @click.prevent="handleDeletePhoto"
                        >
                            Delete Photo
                        </button>
                    </div>

                    <br />

                    <InputError :message="form.errors.photo" />

                    <div
                        v-if="profilePhotoIsDirty === true"
                        class="rounded-md bg-red-50 p-4 shadow-lg"
                    >
                        <div class="flex items-center">
                            <div class="ml-3">
                                <p
                                    class="text-sm font-normal text-red-800 pr-4"
                                >
                                    Image and form not saved.
                                    <span
                                        @click="updateProfileInformation"
                                        class="cursor-pointer myPrimaryLink font-medium italic"
                                    >
                                        Save image.
                                    </span>
                                </p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button
                                        type="button"
                                        class="inline-flex rounded-md bg-red-50 p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-green-50"
                                    ></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                <div
                    @click="handleUploadCoverImage"
                    class="myPrimaryFakeSelect"
                >
                    <div class="relative flex items-center w-full py-0 p-0">
                        <span>
                            {{
                                form.cover_image &&
                                form.cover_image?.length === 0
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
                    v-if="form.cover_image && form.cover_image?.length === 0"
                    class="space-y-6 mt-2"
                >
                    <p class="myPrimaryParagraph">No items selected.</p>
                </div>

                <div>
                    <p
                        v-if="
                            form.cover_image && form.cover_image?.length !== 0
                        "
                        class="py-4"
                    >
                        Added
                        {{ form.cover_image && form.cover_image?.length }}
                        {{
                            form.cover_image && form.cover_image?.length === 1
                                ? "Item"
                                : "Items"
                        }}
                    </p>
                    <div
                        v-if="
                            form.cover_image &&
                            Array.isArray(form?.cover_image) &&
                            form.cover_image?.length !== 0
                        "
                        class="p-2 border border-myPrimaryLightGrayColor"
                    >
                        <div
                            class="min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll divide-y divide-gray-200 pr-2"
                        >
                            <div
                                v-for="image in form.cover_image !== null &&
                                form.cover_image"
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
                                            class="myPrimaryTag bg-myPrimaryLinkColor text-white w-full break-keep"
                                            v-if="
                                                image?.pivot?.primary &&
                                                form.cover_image.length > 1
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
                                                form.cover_image?.length > 1
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
                                form.cover_image &&
                                form.cover_image?.length >= 1
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

                <InputError :message="form.errors.cover_image" />
            </div>
            <!-- Profile photo # end -->

            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Information
                    </div>
                </div>

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
                                            filteredCountries.length === 0 &&
                                            query !== ''
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
                                                    'font-normal': !selected,
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
                                                    'text-gray-800': !active,
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
            </div>
            <DynamicModal
                :show="modalShowDeletePhoto"
                :type="typeModal"
                :gridColumnAmount="gridColumnModal"
                :title="titleModal"
                :description="descriptionModal"
                :firstButtonText="firstButtonModal"
                :secondButtonText="secondButtonModal"
                :thirdButtonText="thirdButtonModal"
                @firstModalButtonFunction="firstModalButtonFunction"
                @secondModalButtonFunction="secondModalButtonFunction"
                @thirdModalButtonFunction="thirdModalButtonFunction"
            >
                <header></header>
                <main></main>
            </DynamicModal>
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
