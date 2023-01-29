<script setup>
import { useForm } from "@inertiajs/vue3";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import SubmitButton from "../../../Components/Buttons/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { computed, ref, watch } from "@vue/runtime-core";

const createTeamForm = useForm({
    name: "",
});

const handleCreateTeam = () => {
    createTeamForm.post(route("company.store"), {
        // error bag validation
        preserveScroll: true,
        onSuccess: () => {
            createTeamForm.reset();
        },
        onError: () => {
            createTeamForm.reset();
        },
        onFinish: () => {
            createTeamForm.reset();
        },
    });
};
</script>

<template>
    <FormSection @submitted="handleCreateTeam">
        <template #title> Team Details </template>

        <template #description>
            Create a new team to collaborate with others on projects.
        </template>

        <template #form>
            <div class="col-span-6">
                <InputLabel value="Team Owner" />

                <div class="flex items-center mt-2">
                    <img
                        class="object-cover w-12 h-12 rounded-full"
                        :src="$page.props.user.profile_photo_url"
                        :alt="$page.props.user.name"
                    />

                    <div class="ml-4 leading-tight">
                        <div>{{ $page.props.user.name }}</div>
                        <div class="text-sm text-gray-700">
                            {{ $page.props.user.email }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="Team Name" />
                <TextInput
                    id="name"
                    v-model="createTeamForm.name"
                    type="text"
                    class="block w-full mt-1"
                    autofocus
                    autocomplete="off"
                />
                <InputError
                    :message="createTeamForm.errors.name"
                    class="mt-2"
                />
            </div>
        </template>

        <template #actions>
            <SubmitButton
                :class="{ 'opacity-25': createTeamForm.processing }"
                :disabled="createTeamForm.processing"
            >
                Create
            </SubmitButton>
        </template>
    </FormSection>
</template>
