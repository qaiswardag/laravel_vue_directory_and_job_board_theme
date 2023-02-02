<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/Logos/AuthenticationCardLogo.vue";
import Checkbox from "@/Components/Forms/Checkbox.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";

const form = useForm({
    first_name: "",
    last_name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        // error bag validation
        errorBag: "createUser",
        preserveScroll: true,
        onSuccess: (log) => {
            form.reset();
        },
        onError: (err) => {},
        onFinish: () => {
            // form.reset("password", "password_confirmation");
        },
    });
};
</script>

<template>
    <GuestsLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Register</h2>
        </template>
        <Head title="Register" />

        <div
            class="bg-[url('/images/app-images/background-images/register/register.jpg')] bg-cover bg-no-repeat bg-fixed bg-center pt-8 pb-32"
        >
            <AuthenticationCard>
                <template #logo>
                    <div
                        class="w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden"
                    ></div>
                </template>

                <form @submit.prevent="submit">
                    <AuthenticationCardLogo
                        class="flex justify-center my-4"
                    ></AuthenticationCardLogo>
                    <div class="mt-4 mb-8">
                        <h1 class="mySecondaryHeader text-center">
                            Sign up and get started today
                        </h1>
                        <p class="myPrimaryParagraph text-center">
                            Millions of designers and agencies.
                        </p>
                    </div>
                    <div class="myInputsFamily">
                        <div class="myInputGroup">
                            <InputLabel for="first_name" value="First name" />
                            <TextInput
                                id="first_name"
                                v-model="form.first_name"
                                type="text"
                                class="mt-1 block w-full"
                                autofocus
                                autocomplete="first_name"
                            />
                            <InputError :message="form.errors.first_name" />
                        </div>
                        <div class="myInputGroup">
                            <InputLabel for="last_name" value="Last name" />
                            <TextInput
                                id="last_name"
                                v-model="form.last_name"
                                type="text"
                                class="mt-1 block w-full"
                                autofocus
                                autocomplete="last_name"
                            />
                            <InputError :message="form.errors.last_name" />
                        </div>
                    </div>

                    <div class="myInputGroup">
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="myInputGroup">
                        <InputLabel for="password" value="Password" />
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="myInputGroup">
                        <InputLabel
                            for="password_confirmation"
                            value="Confirm Password"
                        />
                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                        />
                        <InputError
                            :message="form.errors.password_confirmation"
                        />
                    </div>

                    <div
                        v-if="
                            $page.props.jetstream
                                .hasTermsAndPrivacyPolicyFeature
                        "
                        class="mt-4"
                    >
                        <InputLabel for="terms">
                            <div class="flex items-center">
                                <Checkbox
                                    id="terms"
                                    v-model:checked="form.terms"
                                    name="terms"
                                    required
                                />

                                <div class="ml-2">
                                    I agree to the
                                    <a
                                        target="_blank"
                                        :href="route('terms.show')"
                                        class="underline text-sm text-gray-600 hover:text-gray-900"
                                        >Terms of Service</a
                                    >
                                    and
                                    <a
                                        target="_blank"
                                        :href="route('policy.show')"
                                        class="underline text-sm text-gray-600 hover:text-gray-900"
                                        >Privacy Policy</a
                                    >
                                </div>
                            </div>
                            <InputError :message="form.errors.terms" />
                        </InputLabel>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <Link :href="route('login')" class="myPrimaryLink">
                            Already registered?
                        </Link>

                        <SubmitButton
                            :disabled="form.processing"
                            buttonText="Register"
                        >
                        </SubmitButton>
                    </div>
                </form>
            </AuthenticationCard>
        </div>
    </GuestsLayout>
</template>
