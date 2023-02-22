<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";
import LogoutOtherBrowserSessionsForm from "@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";
import { useStore } from "vuex";
import { computed } from "vue";
import { router } from "@inertiajs/vue3";
import SideBarLink from "@/Components/MenuLinks/SideBarLink.vue";

const props = defineProps({
    sessions: Array,
    confirmsTwoFactorAuthentication: Boolean,
});
</script>

<template>
    <LoggedInLayout title="Profile Security">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Profile Security</h2>
        </template>
        <template #description> Profile Security </template>

        <LogoutOtherBrowserSessionsForm
            :sessions="sessions"
            :confirmsTwoFactorAuthentication="confirmsTwoFactorAuthentication"
        />
        <SectionBorder />
        <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
            <DeleteUserForm />
        </template>
    </LoggedInLayout>
</template>
