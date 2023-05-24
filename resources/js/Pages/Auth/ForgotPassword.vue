<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/Cards/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/Logos/AuthenticationCardLogo.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import SubmitButton from "../../Components/Buttons/SubmitButton.vue";

defineProps({
    status: String,
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <GuestsLayout>
        <Head title="Register" />
        <template #header>
            <div class="myPrimaryMainPageHeaderParagraph">
                <h1 class="myPrimaryMainPageHeaderNotLoggedIn">
                    Forgot password
                </h1>
                <p class="myPrimaryMainPageParagraphNotLoggedIn">
                    Dolor sit amet consectetur adipisicing elit. Culpa illo ipsa
                    fugit quo ad, et, placeat minima assumenda soluta asperiores
                    quaerat perspiciatis.
                </p>
            </div>
        </template>

        <AuthenticationCard :css="'opacity-100'">
            <template #logo>
                <AuthenticationCardLogo />
            </template>

            <div class="mb-4 text-sm text-myPrimaryDarkGrayColor">
                Forgot your password? No problem. Just let us know your email
                address and we will email you a password reset link that will
                allow you to choose a new one.
            </div>

            <div
                v-if="status"
                class="mb-4 font-normal text-sm text-myPrimaryLinkColor"
            >
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div class="myInputGroup">
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        autofocus
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <SubmitButton
                        :disabled="form.processing"
                        buttonText="Email Password Reset Link"
                    >
                    </SubmitButton>
                </div>
            </form>
        </AuthenticationCard>
    </GuestsLayout>
</template>
