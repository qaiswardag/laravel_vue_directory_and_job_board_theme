<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionSection from "@/Components/Actions/ActionSection.vue";
import DangerButton from "@/Components/Buttons/DangerButton.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SecondaryButton from "@/Components/Buttons/SecondaryButton.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";

const confirmingUserDeletion = ref(false); // old modal
const modalShowConfirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const showingNavigationDropdown = ref(false);
const modalShowSwitchTeams = ref(false);
const modalShowLogout = ref(false);

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

const handleUserDeletion = function () {
    // handle show modal for unique content
    modalShowConfirmingUserDeletion.value = true;

    typeModal.value = "danger";
    gridColumnModal.value = 2;
    titleModal.value = "Permanently delete your account?";
    descriptionModal.value =
        "Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowConfirmingUserDeletion.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowConfirmingUserDeletion.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        // delete
        deleteUserProfile();
    };
    // end modal
};

const deleteUserProfile = () => {
    form.delete(route("current-user.destroy"), {
        preserveScroll: true,
        onSuccess: () => {
            // handle show modal for unique content
            modalShowConfirmingUserDeletion.value = false;
        },
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
    form.delete(route("current-user.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <ActionSection :sidebarArea="false">
        <template #title> Delete Account </template>

        <template #description> Permanently delete your account. </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myInputGroup">
                    <p class="myPrimaryParagraph">
                        When you delete your account, your profile, photos,
                        videos, comments, likes and followers will be
                        permanently removed.
                    </p>
                </div>
            </div>
        </template>

        <template #actions>
            <SubmitButton
                :ButtonStyleDelete="true"
                :disabled="form.processing"
                @firstButtonClick="handleUserDeletion"
                buttonText="Delete Account"
            >
            </SubmitButton>

            <DynamicModal
                :show="modalShowConfirmingUserDeletion"
                :disabled="form.processing"
                disabledWhichButton="thirdButton"
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
                <main>
                    <div class="myInputGroup">
                        <InputLabel for="roles" value="Enter Password" />
                        <TextInput
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            placeholder="Password"
                            @keyup.enter="deleteUserProfile"
                        />

                        <InputError :message="form.errors.password" />
                    </div>
                </main>
            </DynamicModal>
        </template>
    </ActionSection>
</template>
