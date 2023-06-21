<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/Cards/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/Logos/AuthenticationCardLogo.vue";
import Checkbox from "@/Components/Forms/Checkbox.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import EmptySectionBorder from "@/Components/Sections/EmptySectionBorder.vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "jd@myissue.io",
    password: "123456",
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
    <div
        class="bg-[url('https://images.unsplash.com/photo-1661956602116-aa6865609028?ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1364&q=80')] w-full min-h-screen bg-cover"
    >
        <GuestsLayout>
            <Head title="Login" />
            <template #header>
                <div class="myPrimaryMainPageHeaderParagraph">
                    <h1 class="myPrimaryMainPageHeaderNotLoggedIn">Login</h1>
                    <p class="myPrimaryMainPageParagraphNotLoggedIn">
                        Dolor sit amet consectetur adipisicing elit. Culpa illo
                        ipsa fugit quo ad, et, placeat minima assumenda soluta
                        asperiores quaerat perspiciatis.
                    </p>
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
                        <h1 class="mySecondaryHeader text-center">Login</h1>
                        <p class="myPrimaryParagraph text-center">
                            Millions of designers and agencies.
                        </p>
                    </div>
                    <div>
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            autofocus
                        />
                        <p
                            class="myPrimaryParagraph text-xs italic pt-2 text-gray-600"
                        >
                            Email: jd@myissue.io
                        </p>
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
                        <p
                            class="myPrimaryParagraph text-xs italic pt-2 text-gray-600"
                        >
                            Password: 123456
                        </p>
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
</template>
