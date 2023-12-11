<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/Cards/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/Logos/AuthenticationCardLogo.vue";
import Checkbox from "@/Components/Forms/Checkbox.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import EmptySectionBorder from "@/Components/Sections/EmptySectionBorder.vue";
import PageHeader from "@/Components/Headers/PageHeader.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";

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
    <MainLayout>
        <div class="bg-gradient-to-t from-red-50 via-pink-100 to-pink-50">
            <GuestsLayout>
                <Head title="Sign up" />
                <template #header>
                    <PageHeader title="Sign up"></PageHeader>
                </template>

                <FullWidthElement :descriptionArea="false" :headerArea="false">
                    <template #title>Lorem ipsum</template>
                    <template #content>
                        <AuthenticationCard>
                            <form @submit.prevent="submit">
                                <div class="mt-4 mb-8 flex justify-center">
                                    <AuthenticationCardLogo />
                                </div>

                                <div class="myInputsFamily">
                                    <div class="myInputGroup">
                                        <InputLabel
                                            for="first_name"
                                            value="First name"
                                        />
                                        <TextInput
                                            id="first_name"
                                            v-model="form.first_name"
                                            autofocus
                                            type="text"
                                            autocomplete="off"
                                        />
                                        <InputError
                                            :message="form.errors.first_name"
                                        />
                                    </div>
                                    <div class="myInputGroup">
                                        <InputLabel
                                            for="last_name"
                                            value="Last name"
                                        />
                                        <TextInput
                                            id="last_name"
                                            v-model="form.last_name"
                                            type="text"
                                            autocomplete="off"
                                        />
                                        <InputError
                                            :message="form.errors.last_name"
                                        />
                                    </div>
                                </div>

                                <div class="myInputGroup">
                                    <InputLabel for="email" value="Email" />
                                    <TextInput
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        autocomplete="email"
                                    />
                                    <InputError :message="form.errors.email" />
                                </div>

                                <div class="myInputGroup">
                                    <InputLabel
                                        for="password"
                                        value="Password"
                                    />
                                    <TextInput
                                        id="password"
                                        v-model="form.password"
                                        type="password"
                                        autocomplete="new-password"
                                    />
                                    <InputError
                                        :message="form.errors.password"
                                    />
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
                                        autocomplete="new-password"
                                    />
                                    <InputError
                                        :message="
                                            form.errors.password_confirmation
                                        "
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
                                            />

                                            <div class="ml-2">
                                                I agree to the
                                                <a
                                                    target="_blank"
                                                    :href="route('terms.show')"
                                                    class="underline text-sm text-myPrimaryDarkGrayColor hover:text-myPrimaryDarkGrayColor"
                                                    >Terms of Service</a
                                                >
                                                and
                                                <a
                                                    target="_blank"
                                                    :href="route('policy.show')"
                                                    class="underline text-sm text-myPrimaryDarkGrayColor hover:text-myPrimaryDarkGrayColor"
                                                    >Privacy Policy</a
                                                >
                                            </div>
                                        </div>
                                        <InputError
                                            :message="form.errors.terms"
                                        />
                                    </InputLabel>
                                </div>

                                <div
                                    class="flex items-center justify-between mt-4"
                                >
                                    <Link
                                        :href="route('login')"
                                        class="myPrimaryLink"
                                    >
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
                    </template>
                </FullWidthElement>

                <EmptySectionBorder></EmptySectionBorder>
            </GuestsLayout>
        </div>
    </MainLayout>
</template>
