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

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: true,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? "on" : "",
    })).post(route("login"), {
        preserveScroll: true,
        onSuccess: (log) => {
            form.reset();
        },
        onError: (err) => {},
        onFinish: () => {},
    });
};
</script>

<template>
    <Head title="Login" />

    <MainLayout>
        <div class="bg-gradient-to-t from-red-50 via-pink-100 to-pink-50">
            <GuestsLayout>
                <template #header>
                    <PageHeader title="Sign in"></PageHeader>
                </template>

                <FullWidthElement :descriptionArea="false" :headerArea="false">
                    <template #content>
                        <AuthenticationCard>
                            <div
                                v-if="status"
                                class="mb-4 font-normal text-sm text-myPrimaryLinkColor"
                            >
                                {{ status }}
                            </div>

                            <form @submit.prevent="submit">
                                <div class="mt-4 mb-8 flex justify-center">
                                    <AuthenticationCardLogo />
                                </div>
                                <div>
                                    <InputLabel for="email" value="Email" />
                                    <TextInput
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        autofocus
                                    />

                                    <InputError :message="form.errors.email" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel
                                        for="password"
                                        value="Password"
                                    />
                                    <TextInput
                                        id="password"
                                        v-model="form.password"
                                        type="password"
                                        autocomplete="current-password"
                                    />

                                    <InputError
                                        :message="form.errors.password"
                                    />
                                </div>

                                <div class="block mt-4">
                                    <label class="flex items-center">
                                        <Checkbox
                                            v-model:checked="form.remember"
                                            name="remember"
                                        />
                                        <span
                                            class="ml-2 text-sm text-myPrimaryDarkGrayColor"
                                            >Remember me</span
                                        >
                                    </label>
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <SubmitButton
                                        :disabled="form.processing"
                                        buttonText="Log in"
                                    >
                                    </SubmitButton>
                                </div>
                                <div
                                    class="flex items-center justify-between mt-12"
                                >
                                    <Link
                                        :href="route('register')"
                                        class="myPrimaryLink"
                                    >
                                        Sign up
                                    </Link>

                                    <span class="myPrimaryLink"> | </span>
                                    <Link
                                        v-if="canResetPassword"
                                        :href="route('password.request')"
                                        class="myPrimaryLink"
                                    >
                                        Forgot password?
                                    </Link>
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
