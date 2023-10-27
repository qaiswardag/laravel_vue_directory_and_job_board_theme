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
    <MainLayout>
        <div
            class="bg-[url('https://images.unsplash.com/photo-1543728069-a3f97c5a2f32?auto=format&fit=crop&q=80&w=3269&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')] w-full min-h-screen bg-cover"
        >
            <GuestsLayout>
                <Head title="Login" />
                <template #header>
                    <div
                        class="myPrimaryMainPageHeaderParagraph pb-8 text-center"
                    >
                        <h1
                            class="myPrimaryMainPageHeaderNotLoggedIn text-white"
                        >
                            Sign in
                        </h1>
                    </div>
                </template>

                <AuthenticationCard :css="'opacity-100'">
                    <template #logo>
                        <AuthenticationCardLogo />
                    </template>
                    <div
                        v-if="status"
                        class="mb-4 font-normal text-sm text-myPrimaryLinkColor"
                    >
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit">
                        <div class="mt-4 mb-8">
                            <h1 class="mySecondaryHeader text-center">
                                Sign in
                            </h1>
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
                            <InputLabel for="password" value="Password" />
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                autocomplete="current-password"
                            />

                            <InputError :message="form.errors.password" />
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

                        <div class="flex items-center justify-between mt-4">
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="myPrimaryLink"
                            >
                                Forgot your password?
                            </Link>

                            <SubmitButton
                                :disabled="form.processing"
                                buttonText="Log in"
                            >
                            </SubmitButton>
                        </div>
                    </form>
                </AuthenticationCard>
                <EmptySectionBorder></EmptySectionBorder>
            </GuestsLayout>
        </div>
    </MainLayout>
</template>
