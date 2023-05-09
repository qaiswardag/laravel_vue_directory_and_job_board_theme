<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/Cards/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/Logos/AuthenticationCardLogo.vue";
import Checkbox from "@/Components/Forms/Checkbox.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import SubmitButton from "../../Components/Buttons/SubmitButton.vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? "on" : "",
    })).post(route("login"), {
        //
        //
        //
        // error bag validation
        // errorBag: "loginUser",
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
    <GuestsLayout title="Log in">
        <template #header>
            <h1 class="myPrimaryMainPageHeaderNotLoggedIn">Login</h1>
            <p class="myPrimaryMainPageParagraphNotLoggedIn">
                Win new customers with the #1 email marketing and automations
                brand* that recommends ways to get more opens, clicks, and
                sales.
            </p>
        </template>

        <AuthenticationCard :css="'opacity-100'">
            <template #logo>
                <AuthenticationCardLogo />
            </template>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
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
                        <span class="ml-2 text-sm text-myPrimaryDarkGrayColor"
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
    </GuestsLayout>
</template>
