<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";
import LogoutOtherBrowserSessionsForm from "@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";
import { useStore } from "vuex";
import { computed } from "vue";

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
    <LoggedInLayout title="Profile">
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Profile</h2>
        </template>

        <div>
            <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                <UpdateProfileInformationForm :user="$page.props.user" />
            </div>
            <SectionBorder />
            <div v-if="$page.props.jetstream.canUpdatePassword">
                <UpdatePasswordForm class="mt-10 sm:mt-0" />
            </div>
            <SectionBorder />
            <LogoutOtherBrowserSessionsForm
                :sessions="sessions"
                class="mt-10 sm:mt-0"
            />
            <SectionBorder />
            <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                <DeleteUserForm class="mt-10 sm:mt-0" />
            </template>
        </div>
    </LoggedInLayout>
</template>
