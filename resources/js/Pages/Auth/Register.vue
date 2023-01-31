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
    firstName: "",
    lastName: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("userStore"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <!-- <GuestsLayout title="Testing"> -->
    <GuestsLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Register</h2>
        </template>
        <Head title="Register" />

        <AuthenticationCard>
            <template #logo>
                <AuthenticationCardLogo />
            </template>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="firstName" value="First name" />
                    <TextInput
                        id="firstName"
                        v-model="form.firstName"
                        type="text"
                        class="mt-1 block w-full"
                        autofocus
                        autocomplete="firstName"
                    />
                    <InputError class="mt-2" :message="form.errors.firstName" />
                </div>
                <div>
                    <InputLabel for="lastName" value="Last name" />
                    <TextInput
                        id="lastName"
                        v-model="form.lastName"
                        type="text"
                        class="mt-1 block w-full"
                        autofocus
                        autocomplete="lastName"
                    />
                    <InputError class="mt-2" :message="form.errors.lastName" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
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
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <div
                    v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
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
                        <InputError class="mt-2" :message="form.errors.terms" />
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
    </GuestsLayout>
</template>
