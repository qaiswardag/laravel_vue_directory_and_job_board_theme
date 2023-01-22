<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionSection from "@/Components/ActionSection.vue";
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    team: Object,
});

const enablingConfirmPassword = ref(false);

const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const modalShowConfirmingTeamDeletion = ref(false);

const handleDeleteTeam = () => {
    console.log("handle delete team køre");
    deleteTeam();
};

const deleteTeam = function () {
    form.delete(route("teams.destroy", props.team), {
        preserveScroll: true,
        onSuccess: (log) => {
            console.log("team deleted. Everything worked", log);
        },
        onError: (err) => {
            console.log("Error, deleting team:", err); // {password: 'The password is incorrect.'}
        },
        onFinish: () => {
            console.log("everything finished");
        },
    });
};
</script>

<template>
    <ActionSection>
        <template #title> Delete Team </template>

        <template #description> Permanently delete this team. </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Once a team is deleted, all of its resources and data will be
                permanently deleted. Before deleting this team, please download
                any data or information regarding this team that you wish to
                retain.
            </div>

            <!-- TODO: Add "confirms password" middleware to delete team
            right now, team can be deleted without password confirmation -->
            <div class="mt-5">
                <DangerButton
                    type="button"
                    :class="{ 'opacity-25': enablingConfirmPassword }"
                    :disabled="enablingConfirmPassword"
                    @click="deleteTeam"
                >
                    Delete Team
                </DangerButton>
            </div>

            <!-- with confirm password modal - start -->
            <!-- <div class="mt-5">
                <ConfirmsPassword @confirmed="handleDeleteTeam">
                    <DangerButton
                        type="button"
                        :class="{ 'opacity-25': enablingConfirmPassword }"
                        :disabled="enablingConfirmPassword"
                    >
                        Delete Team
                    </DangerButton>
                </ConfirmsPassword>
            </div> -->
            <!-- with confirm password modal - end -->
        </template>
    </ActionSection>
</template>
