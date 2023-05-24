<script setup>
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import ActionSection from "@/Components/ActionSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";

const props = defineProps({
    sessions: Array,
    confirmsTwoFactorAuthentication: Boolean,
});

const modalShowConfirmingSessionLogout = ref(false);
const passwordInput = ref(null);

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

const form = useForm({
    password: "",
});

const handleSessionLogout = () => {
    modalShowConfirmingSessionLogout.value = true;

    // handle show modal for unique content
    modalShowConfirmingSessionLogout.value = true;
    // set modal standards
    typeModal.value = "danger";
    gridColumnModal.value = 2;
    titleModal.value = "Log Out Other Browser Sessions";
    descriptionModal.value =
        "Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Confirm";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowConfirmingSessionLogout.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        // modalShowConfirmingSessionLogout.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        logoutOtherBrowserSessions();
    };
    // end modal

    setTimeout(() => passwordInput.value.focus(), 250);
};

const logoutOtherBrowserSessions = () => {
    form.delete(route("other-browser-sessions.destroy"), {
        preserveScroll: true,
        onSuccess: () => {
            modalShowConfirmingSessionLogout.value = false;
            form.reset();
        },
        onError: () => {
            passwordInput.value.focus();
        },
        onFinish: () => {
            form.reset();
        },
    });
};

const closeModal = () => {
    modalShowConfirmingSessionLogout.value = false;

    form.reset();
};
</script>

<template>
    <ActionSection :sidebarArea="false">
        <template #title> Browser Sessions </template>

        <template #description>
            Manage and log out your active sessions on other browsers and
            devices.
        </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myInputGroup">
                    <!-- Other Browser Sessions -->
                    <div class="mt-5 space-y-6">
                        <div
                            v-for="(session, i) in sessions"
                            :key="i"
                            class="flex items-center"
                        >
                            <div>
                                <svg
                                    v-if="session.agent.is_desktop"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    class="w-8 h-8 text-myPrimaryDarkGrayColor"
                                >
                                    <path
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>

                                <svg
                                    v-else
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="w-8 h-8 text-myPrimaryDarkGrayColor"
                                >
                                    <path d="M0 0h24v24H0z" stroke="none" />
                                    <rect
                                        x="7"
                                        y="4"
                                        width="10"
                                        height="16"
                                        rx="1"
                                    />
                                    <path d="M11 5h2M12 17v.01" />
                                </svg>
                            </div>

                            <div class="ml-3">
                                <div
                                    class="text-sm text-myPrimaryDarkGrayColor"
                                >
                                    {{
                                        session.agent.platform
                                            ? session.agent.platform
                                            : "Unknown"
                                    }}
                                    -
                                    {{
                                        session.agent.browser
                                            ? session.agent.browser
                                            : "Unknown"
                                    }}
                                </div>

                                <div>
                                    <div
                                        class="text-xs text-myPrimaryDarkGrayColor"
                                    >
                                        {{ session.ip_address }},

                                        <span
                                            v-if="session.is_current_device"
                                            class="text-myPrimaryLinkColor font-normal"
                                            >This device</span
                                        >
                                        <span v-else
                                            >Last active
                                            {{ session.last_active }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <SubmitButton
                :ButtonStyleDelete="false"
                :disabled="false"
                @firstButtonClick="handleSessionLogout"
                buttonText="Log Out Other Browser Sessions"
            >
            </SubmitButton>
            <ActionMessage :on="form.recentlySuccessful" type="success"
                >Successfully deleted your Browser Sessions.
            </ActionMessage>

            <DynamicModal
                :show="modalShowConfirmingSessionLogout"
                :type="typeModal"
                :disabled="form.processing"
                disabledWhichButton="thirdButton"
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
                <main>
                    <div class="mt-4">
                        <TextInput
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            placeholder="Password"
                            @keyup.enter="logoutOtherBrowserSessions"
                        />

                        <InputError :message="form.errors.password" />
                    </div>
                </main>
            </DynamicModal>
        </template>
    </ActionSection>
</template>
