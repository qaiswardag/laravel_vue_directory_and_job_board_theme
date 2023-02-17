<script setup>
import { ref } from "vue";
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
import SectionBorder from "@/Components/SectionBorder.vue";

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: "PUT",
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    public: props.user.public ? true : false,
});

const formPhoto = useForm({
    _method: "PUT",
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    public: props.user.public ? true : false,
    photo: null,
});

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

const updateProfileInformation = () => {
    form.post(route("user-profile-information.update"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
        onSuccess: () => {
            clearPhotoFileInput();
        },
        onError: (err) => {
            console.log("not able to update details!", err);
        },
        onFinish: () => {},
    });
};
const updateProfilePhoto = () => {
    if (photoInput.value) {
        formPhoto.photo = photoInput.value.files[0];
    }

    formPhoto.post(route("user-profile-information.update"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
        onSuccess: () => {
            clearPhotoFileInput();
        },
        onError: (err) => {
            console.log("not able to update details!", err);
        },
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
</script>

<template>
    <FormSection @submitted="updateProfilePhoto" :sidebarArea="false">
        <template #title> Profile Photo </template>

        <template #description> Update your account's profile photo. </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myInputGroup">
                    <input
                        ref="photoInput"
                        type="file"
                        class="hidden"
                        @change="updatePhotoPreview"
                    />
                    <div
                        class="flex flex-col justify-center items-center gap-2 mb-8 mt-4"
                    >
                        <div
                            v-show="
                                !photoPreview &&
                                user.profile_photo_path !== null
                            "
                            class="mt-2"
                        >
                            <img
                                class="object-cover w-16 h-16 rounded-full"
                                :src="user.profile_photo_url"
                                :alt="user.first_name + user.last_name"
                            />
                        </div>

                        <div v-show="photoPreview !== null">
                            <span
                                class="block rounded-full w-16 h-16 bg-cover bg-no-repeat bg-center"
                                :style="
                                    'background-image: url(\'' +
                                    photoPreview +
                                    '\');'
                                "
                            />
                        </div>

                        <div
                            v-show="
                                user.profile_photo_path === null &&
                                !photoPreview
                            "
                            class="w-16 h-16 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-semibold text-white"
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
                        <SecondaryButton
                            class="myPrimaryButton"
                            type="button"
                            @click.prevent="selectNewPhoto"
                        >
                            Upload Photo
                        </SecondaryButton>

                        <SecondaryButton
                            v-show="
                                photoPreview || user.profile_photo_path !== null
                            "
                            type="button"
                            class="myPrimaryDeleteButton"
                            @click.prevent="handleDeletePhoto"
                        >
                            Delete Photo
                        </SecondaryButton>
                    </div>

                    <InputError :message="form.errors.photo" />
                </div>
            </div>
        </template>

        <template #actions>
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
            <SubmitButton :disabled="formPhoto.processing" buttonText="Update">
            </SubmitButton>
            <ActionMessage :on="formPhoto.recentlySuccessful" type="success">
                Successfully updated your Profile Information.
            </ActionMessage>
        </template>
    </FormSection>
    <SectionBorder />
    <FormSection @submitted="updateProfileInformation">
        <template #title> Profile Details </template>

        <template #description>
            Update your account's profile information.
        </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myInputGroup">
                    <InputLabel for="first_name" value="First name" />
                    <TextInput
                        id="first_name"
                        v-model="form.first_name"
                        type="text"
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
                        autocomplete="last_name"
                    />
                    <InputError :message="form.errors.last_name" />
                </div>

                <!-- Email -->
                <div class="myInputGroup">
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" v-model="form.email" type="email" />
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
                            class="mt-2 font-medium text-sm text-green-600"
                        >
                            A new verification link has been sent to your email
                            address.
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #sidebar>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Status</div>
                    <p class="myPrimaryParagraph">Specify Your status.</p>
                </div>
                <div
                    class="myInputGroup flex myPrimaryGap flex-row-reverse justify-end"
                >
                    <InputLabel
                        :value="form.public ? 'Public' : 'Private'"
                        :class="{
                            'text-myPrimaryBrandColor': form.public,
                            'text-myErrorColor': !form.public,
                        }"
                    />
                    <Switch
                        v-model="form.public"
                        :class="[
                            form.public
                                ? 'bg-myPrimaryBrandColor'
                                : 'bg-gray-200',
                            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-myPrimaryBrandColor focus:ring-offset-2',
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
                                        stroke-width="2"
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
                                    class="h-3 w-3 text-myPrimaryBrandColor"
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
        </template>
        <template #actions>
            <SubmitButton :disabled="form.processing" buttonText="Update">
            </SubmitButton>
            <ActionMessage :on="form.recentlySuccessful" type="success">
                Successfully updated your Profile Information.
            </ActionMessage>
        </template>
    </FormSection>
</template>
