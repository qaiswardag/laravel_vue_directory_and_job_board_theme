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

const form = useForm({
    name: "",
    public: true,
});

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
    titleModal.value = `Create a new team ${form.name}`;
    descriptionModal.value = `Please confirm creating a new team.`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Create Team";
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

const teamUsername = computed(() => {
    return form.name;
});

const teamSlugName = ref("");

watch(
    () => teamUsername.value,
    (newValue) => {
        teamSlugName.value = slugify(newValue, config.slugifyOptions);
    },
    { immediate: true }
);

const createTeam = () => {
    form.post(route("teams.store"), {
        // error bag validation
        errorBag: "createTeam",
        preserveScroll: true,
        onSuccess: (log) => {
            form.reset();
        },
        onError: (err) => {
            form.reset();
        },
        onFinish: () => {},
    });
};
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
        <template #title> Create Team </template>

        <template #description>
            Create a new team to collaborate with others on projects.
        </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Team slug</div>
                </div>
                <div class="myInputGroup">
                    <InputLabel for="team_slug" value="Team slug" />
                    <div class="relative flex items-center">
                        <TextInput
                            id="team_slug"
                            :value="teamSlugName"
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
                        Team slug name is based on the team name. Be aware that
                        when updating the team name, it can affect search engine
                        optimization for the team and resources related to the
                        team.
                    </p>
                </div>

                <SectionBorder></SectionBorder>
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Team Name</div>
                </div>
                <div class="myInputGroup">
                    <InputLabel for="name" value="Team Name" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        autofocus
                        autocomplete="off"
                    />
                    <InputError :message="form.errors.name" />
                </div>
            </div>
        </template>
        <template #sidebar>
            <!-- resoruce status # start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Status</div>
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
            <!-- resoruce status # end -->
        </template>
        <template #actions>
            <SubmitButton :disabled="form.processing" buttonText="Create Team">
            </SubmitButton>
            <ActionMessage :on="form.recentlySuccessful" type="success">
                Successfully Created your Team.
            </ActionMessage>
        </template>
    </FormSection>
</template>
