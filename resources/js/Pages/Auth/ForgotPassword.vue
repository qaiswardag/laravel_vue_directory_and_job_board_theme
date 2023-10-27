<script setup>
import { useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/Cards/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/Logos/AuthenticationCardLogo.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import SubmitButton from "../../Components/Buttons/SubmitButton.vue";
import EmptySectionBorder from "../../Components/Sections/EmptySectionBorder.vue";

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
    <MainLayout>
        <GuestsLayout>
            <Head title="Forgot password" />
            <template #header>
                <div class="myPrimaryMainPageHeaderParagraph pb-8 text-center">
                    <h1 class="myPrimaryMainPageHeaderNotLoggedIn">
                        Forgot password
                    </h1>
                </div>
            </template>

            <AuthenticationCard :css="'opacity-100'">
                <template #logo>
                    <AuthenticationCardLogo />
                </template>

                <div class="mt-4 mb-8">
                    <h1 class="mySecondaryHeader text-center">
                        Sign up and get started today
                    </h1>
                    <p class="myPrimaryParagraph text-center">
                        Forgot your password? No problem. Just let us know your
                        email address and we will email you a password reset
                        link that will allow you to choose a new one.
                    </p>
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
            <EmptySectionBorder></EmptySectionBorder>
        </GuestsLayout>
    </MainLayout>
</template>
