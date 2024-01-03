<script setup>
import { useForm } from "@inertiajs/vue3";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import AvatarCardCenterSmall from "@/Components/Avatars/AvatarCardCenterSmall.vue";
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import { Switch } from "@headlessui/vue";
import { ref, computed } from "@vue/reactivity";
import slugify from "slugify";
import config from "@/utils/config";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";
import { onBeforeMount, onMounted, watch } from "vue";
import NotificationsFixedBottom from "@/Components/Modals/NotificationsFixedBottom.vue";
import { useStore } from "vuex";

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

const store = useStore();

const isSlugEditable = ref(false);
const slugValueTeamName = ref("");
const slugValueCustom = ref("");

const postForm = useForm({
    name: "",
    slug: "",
    public: true,
});

// The code uses the watch function from Vue 3 to watch for changes to the
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

const modalShowCreateTeam = ref(false);

const handleCreateTeam = function () {
    // handle show modal for unique content
    modalShowCreateTeam.value = true;
    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = `Create a new team ${postForm.name}`;
    descriptionModal.value = `Please confirm creating new Company.`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Create Company";
    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowCreateTeam.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        createTeam();
        // handle show modal for unique content
        modalShowCreateTeam.value = false;
    };
    // end modal
};

const showErrorNotifications = ref(false);

const notificationsModalButton = function () {
    showErrorNotifications.value = false;
};

const createTeam = () => {
    postForm.post(route("teams.store.team"), {
        preserveScroll: true,
        onSuccess: () => {
            store.commit("mediaLibrary/setCurrentImage", null);
            store.commit("mediaLibrary/setCurrentPreviewImage", null);
            store.commit("attachedUsersOrItems/setRemoveAttachedUser", []);
        },
        onError: () => {},
        onFinish: () => {},
    });
};

// get unique post if needs to be updated
onBeforeMount(() => {});
</script>

<template>
    <DynamicModal
        :show="modalShowCreateTeam"
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
    <FormSection
        @submitted="handleCreateTeam"
        :sidebarArea="true"
        :actionsArea="true"
    >
        <template #title> Create Company </template>

        <template #description> Create new company. </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Company Information
                    </div>
                </div>
                <div class="myInputGroup">
                    <InputLabel for="name" value="Company Name" />
                    <TextInput
                        placeholder="Name.."
                        id="name"
                        v-model="postForm.name"
                        type="text"
                        autocomplete="off"
                    />
                    <InputError :message="postForm.errors.name" />
                </div>

                <SectionBorder></SectionBorder>

                <!-- post slug start -->
                <div class="myInputGroup">
                    <div v-show="isSlugEditable === false">
                        <InputLabel for="slug" value="Slug" />
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
                <!-- post slug end -->
            </div>
        </template>
        <template #sidebar>
            <!-- resoruce status # start -->
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
            <!-- resoruce status # end -->
        </template>
        <template #actions>
            <SubmitButton
                :disabled="postForm.processing"
                buttonText="Create Company"
            >
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
                Successfully Created your Company.
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
