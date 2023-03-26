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

// store
const store = useStore();

const props = defineProps({
    team: Object,
    permissions: Object,
});

const handleUpdateTeam = function () {
    updateTeam();

    // end modal
};

const form = useForm({
    name: props.team.name,
    thumbnail: props.team.thumbnail,
    logo: props.team.logo,
    public: props.team.public ? true : false,
});

const updateTeam = () => {
    form.put(route("teams.update", props.team), {
        // error bag validation
        errorBag: "updateTeam",
        preserveScroll: true,
        onSuccess: (log) => {},
        onError: (err) => {},
        onFinish: () => {},
    });
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
        form.thumbnail = getCurrentImage.value.currentImage.mediaLibrary.path;
        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    // end modal
};
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
        form.logo = getCurrentImage.value.currentImage.mediaLibrary.path;
        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    // end modal
};

const handleRemoveCoverImage = function () {
    form.thumbnail = null;
};
const handleRemoveLogo = function () {
    form.logo = null;
};
</script>

<template>
    <FormSection @submitted="handleUpdateTeam" :sidebarArea="true">
        <template #title>
            {{ $page.props.team && $page.props.team.name }}
        </template>

        <template #description>
            <div
                v-if="
                    $page.props.team.owner &&
                    $page.props.team.owner.id === $page.props.user.id
                "
            >
                <p class="group flex items-center pr-2 py-2 myPrimaryParagraph">
                    Your current Role is

                    {{ $page.props.currentUserTeamRole }}
                </p>
            </div>
            <div
                v-if="
                    $page.props.team.owner &&
                    $page.props.team.owner.id !== $page.props.user.id
                "
            >
                <p class="myPrimaryParagraph">
                    Team owner is
                    <span>
                        {{
                            $page.props.team.owner &&
                            $page.props.team.owner.first_name
                        }}
                        {{
                            $page.props.team.owner &&
                            $page.props.team.owner.last_name
                        }}
                    </span>
                </p>
                <p class="group flex items-center pr-2 myPrimaryParagraph">
                    Your current Role is
                    {{ $page.props.currentUserTeamRole }}
                </p>
            </div>
        </template>

        <template #main>
            <!-- Team Owner Information -->
            <div class="myInputsOrganization">
                <div class="myInputGroup">
                    <InputLabel for="name" value="Update Team Name" />
                    <TextInput id="name" v-model="form.name" type="text" />
                    <InputError :message="form.errors.name" />
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
            <!-- post status - end -->
            <!-- logo - start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Logo</div>
                    <p class="myPrimaryParagraph">
                        Lorem ipsum dolor sit amet.
                    </p>
                </div>
                <img
                    v-if="form.logo && form.logo.length !== 0"
                    @click="handleUploadLogo"
                    class="myPrimarythumbnailLogo"
                    alt="Logo"
                    :src="`/uploads/${form.logo}`"
                />
                <div
                    class="myInputGroup flex items-center justify-between border-t border-myPrimaryLightGrayColor pt-4"
                >
                    <button
                        @click="handleUploadLogo"
                        type="button"
                        class="myPrimaryButton gap-2 items-center"
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
                            />
                        </svg>

                        Logo
                    </button>
                    <div v-if="form && form.logo">
                        <svg
                            @click="handleRemoveLogo"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5 text-myErrorColor cursor-pointer"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                            />
                        </svg>
                    </div>
                </div>
                <InputError :message="form.errors.logo" />
            </div>
            <!-- logo - end -->
            <!-- cover image - start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Cover Image
                    </div>
                    <p class="myPrimaryParagraph">
                        Lorem ipsum dolor sit amet.
                    </p>
                </div>
                <img
                    v-if="form.thumbnail && form.thumbnail.length !== 0"
                    @click="handleUploadCoverImage"
                    class="myPrimarythumbnailInsertPreview"
                    alt="cover image"
                    :src="`/uploads/${form.thumbnail}`"
                />
                <div
                    class="myInputGroup flex items-center justify-between border-t border-myPrimaryLightGrayColor pt-4"
                >
                    <button
                        @click="handleUploadCoverImage"
                        type="button"
                        class="myPrimaryButton gap-2 items-center"
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
                            />
                        </svg>

                        Cover Image
                    </button>
                    <div v-if="form && form.thumbnail">
                        <svg
                            @click="handleRemoveCoverImage"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5 text-myErrorColor cursor-pointer"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                            />
                        </svg>
                    </div>
                </div>
                <InputError :message="form.errors.thumbnail" />
            </div>
            <!-- cover image - end -->

            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Team owner
                    </div>
                </div>
                <div class="flex flex-col justify-start gap-2 mb-8 mt-4">
                    <div
                        v-if="
                            $page.props.team.owner &&
                            $page.props.team.owner.profile_photo_path !== null
                        "
                        class="mt-2"
                    >
                        <img
                            class="object-cover w-16 h-16 rounded-full"
                            :src="`/uploads/${$page.props.team.owner.profile_photo_path}`"
                            :alt="
                                $page.props.team.owner.first_name +
                                $page.props.team.owner.last_name
                            "
                        />
                    </div>

                    <div
                        v-if="
                            $page.props.team.owner &&
                            $page.props.team.owner.profile_photo_path === null
                        "
                        class="w-16 h-16 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-semibold text-white"
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
                    <span class="flex flex-col gap-1 myPrimaryParagraph">
                        <span>
                            {{ $page.props.team.owner.first_name }}
                            {{ $page.props.team.owner.last_name }}
                        </span>
                    </span>
                </div>
            </div>

            <MediaLibraryModal
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
            <SubmitButton :disabled="form.processing" buttonText="Update">
            </SubmitButton>
            <ActionMessage :on="form.recentlySuccessful" type="success">
                Successfully updated your team.
            </ActionMessage>
        </template>
    </FormSection>
</template>
