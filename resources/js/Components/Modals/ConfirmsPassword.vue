<script setup>
import { ref, reactive, nextTick } from "vue";
import InputError from "../InputError.vue";
import TextInput from "../TextInput.vue";
import DynamicModal from "./DynamicModal.vue";

const emit = defineEmits(["confirmed"]);

const props = defineProps({
    title: {
        type: String,
        default: "Confirm Password",
    },
    content: {
        type: String,
        default: "For your security, please confirm your password to continue.",
    },
    button: {
        type: String,
        default: "Confirm",
    },
});

// show modal for confirming password
const modalConfirmingPassword = ref(false);

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

const form = reactive({
    password: "",
    error: "",
    processing: false,
});

const passwordInput = ref(null);

const startmodalConfirmingPassword = () => {
    axios.get(route("password.confirmation")).then((response) => {
        if (response.data.confirmed) {
            emit("confirmed");
        } else {
            modalConfirmingPassword.value = true;

            // set modal standards
            typeModal.value = "success";
            gridColumnModal.value = 2;
            titleModal.value = props.title;
            descriptionModal.value = props.content;
            firstButtonModal.value = "Close";
            secondButtonModal.value = null;
            thirdButtonModal.value = props.button;

            // handle click
            firstModalButtonFunction.value = function () {
                // handle show modal for unique content
                modalConfirmingPassword.value = false;
            };
            // handle click
            thirdModalButtonFunction.value = function () {
                // confirm password
                confirmPassword();
            };
            // end modal

            setTimeout(() => passwordInput.value.focus(), 250);
        }
    });
};

const confirmPassword = () => {
    form.processing = true;

    axios
        .post(route("password.confirm"), {
            password: form.password,
        })
        .then(() => {
            form.processing = false;

            closeModal();
            nextTick().then(() => emit("confirmed"));
        })
        .catch((error) => {
            form.processing = false;
            form.error = error.response.data.errors.password[0];
            passwordInput.value.focus();
        });
};

const closeModal = () => {
    modalConfirmingPassword.value = false;
    form.password = "";
    form.error = "";
};
</script>

<template>
    <span>
        <span @click="startmodalConfirmingPassword">
            <slot />
        </span>

        <DynamicModal
            :show="modalConfirmingPassword"
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
                        @keyup.enter="confirmPassword"
                    />
                    <InputError :message="form.error" class="mt-2" />
                </div>
            </main>
        </DynamicModal>
    </span>
</template>
