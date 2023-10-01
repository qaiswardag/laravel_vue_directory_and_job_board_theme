<script setup>
import { ref, watch } from "vue";
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

const props = defineProps({
    team: {
        required: true,
    },
});

const modalShowModal = ref(false);
const form = useForm({
    _method: "PUT",
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

const updateProfileInformation = () => {
    // if (photoInput.value) {
    //     form.photo = photoInput.value.files[0];
    // }
    // form.post(route("user-profile-information.update"), {
    //     errorBag: "updateProfileInformation",
    //     preserveScroll: true,
    //     onSuccess: () => {
    //         clearPhotoFileInput();
    //         profilePhotoIsDirty.value = false;
    //     },
    //     onError: (err) => {},
    //     onFinish: () => {},
    // });
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title> Payment Form </template>

        <template #description> Payment form </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Subscription
                    </div>
                    <p class="myPrimaryParagraph">
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit. Cupiditate, ratione.
                    </p>
                </div>
            </div>

            <DynamicModal
                :show="modalShowModal"
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
            <SubmitButton :disabled="form.processing" buttonText="Save">
            </SubmitButton>
            <ActionMessage :on="form.recentlySuccessful" type="success">
                Successfully updated.
            </ActionMessage>
        </template>
    </FormSection>
</template>
