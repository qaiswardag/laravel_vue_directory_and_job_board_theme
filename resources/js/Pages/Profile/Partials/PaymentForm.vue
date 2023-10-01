<script setup>
import { onBeforeMount, ref, watch } from "vue";
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
    user: {
        required: true,
    },
});

const modalShowModal = ref(false);

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

const formType = ref("create");

const form = useForm({
    team: props.team,
    user_id: props.user.id,
});

const handleSubmit = () => {
    // try to store post
    createOrUpdate();
};

const createOrUpdate = () => {
    if (formType.value === "create") {
        console.log(`came here..`);
        form.post(route("team.stores.store.subscription"), {
            preserveScroll: true,
            onSuccess: () => {
                clearForm();
            },
            onError: () => {},
            onFinish: () => {},
        });
    }

    // if (formType.value === "update") {
    //     form.post(route("team.stores.update.subscription", props.post.id), {
    //         preserveScroll: true,
    //         onSuccess: () => {
    //             submittedOnUpdate.value = false;
    //             clearPageBuilderOnSuccessUpdate();
    //         },
    //         onError: () => {},
    //         onFinish: () => {},
    //     });
    // }
};

onBeforeMount(() => {
    if (props.post) {
        console.log(`ook:`, props.post);
        formType.value = "update";
    }
});
</script>

<template>
    <FormSection @submitted="handleSubmit">
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
        </template>
        <template #sidebar>
            <!-- post status - start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Status</div>
                    <p class="myPrimaryParagraph">Specify post status.</p>
                </div>
            </div>
        </template>

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

        <template #actions>
            <SubmitButton :disabled="form.processing" buttonText="Save">
            </SubmitButton>
            <ActionMessage :on="form.recentlySuccessful" type="success">
                Successfully updated.
            </ActionMessage>
        </template>
    </FormSection>
</template>
