<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import DynamicModal from "../../../Components/Modals/DynamicModal.vue";
import ActionSection from "@/Components/ActionSection.vue";
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";

// show modal for user deletion
const modalShowUserDeletion = ref(false);

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

const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const handleUserDeletion = () => {
    // handle show modal for unique content
    modalShowUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);

    // set modal standards
    typeModal.value = "danger";
    gridColumnModal.value = 2;
    titleModal.value = "Permanently delete your account";
    descriptionModal.value =
        "Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be                    permanently deleted. Please enter your password to confirm you would like to permanently delete your account.";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowUserDeletion.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowUserDeletion.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        deleteUser();
    };
    // end modal
};

const deleteUser = () => {
    form.delete(route("current-user.destroy"), {
        preserveScroll: true,
        onSuccess: () => {
            // handle show modal for unique content
            modalShowUserDeletion.value = false;
        },
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <ActionSection>
        <template #title> Delete Account </template>

        <template #description> Permanently delete your account. </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </div>
            <div class="mt-5">
                <DangerButton @click="handleUserDeletion">
                    Delete Account
                </DangerButton>
            </div>
            <DynamicModal
                :show="modalShowUserDeletion"
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
                    <div class="mt-4">
                        <label for="name" class="myPrimaryInputLabel"
                            >Password
                        </label>
                        <TextInput
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block"
                            placeholder="Password"
                            @keyup.enter="deleteUser"
                        />

                        <InputError
                            :message="form.errors.password"
                            class="mt-2"
                        />
                    </div>
                </main>
            </DynamicModal>
        </template>
    </ActionSection>
</template>
