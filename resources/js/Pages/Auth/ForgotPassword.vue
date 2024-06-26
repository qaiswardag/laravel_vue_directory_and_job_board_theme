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
import PageHeader from "@/Components/Headers/PageHeader.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";

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
    <Head title="Forgot password">
        <meta
            head-key="description"
            name="description"
            content="Forgot your password? No problem. Just let us know your email address and we will email you a password reset link."
        />
    </Head>

    <MainLayout>
        <div class="bg-gradient-to-t from-red-50 via-gray-200 to-pink-50">
            <GuestsLayout>
                <template #header>
                    <PageHeader title="Forgot password"></PageHeader>
                </template>

                <FullWidthElement :descriptionArea="false" :headerArea="false">
                    <template #title>Lorem ipsum</template>
                    <template #content>
                        <AuthenticationCard>
                            <div class="mt-4 mb-8 flex justify-center">
                                <AuthenticationCardLogo />
                            </div>
                            <p class="myPrimaryParagraph text-center mt-4 mb-6">
                                Forgot your password? No problem. Just let us
                                know your email address and we will email you a
                                password reset link that will allow you to
                                choose a new one.
                            </p>

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
                                        buttonText="Reset password"
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
