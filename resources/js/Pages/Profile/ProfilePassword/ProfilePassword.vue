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
</script>

<template>
    <LoggedInLayout title="Profile">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Update Password</h2>
        </template>
        <template #description>Update Profile</template>

        <div>
            <div v-if="$page.props.jetstream.canUpdatePassword">
                <UpdatePasswordForm />
            </div>
        </div>
    </LoggedInLayout>
</template>
