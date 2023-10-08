<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";
import LogoutOtherBrowserSessionsForm from "@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";
import { useStore } from "vuex";
import { computed } from "vue";
import { router } from "@inertiajs/vue3";
import SideBarLink from "@/Components/MenuLinks/SideBarLink.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";

const props = defineProps({
    sessions: Array,
    confirmsTwoFactorAuthentication: Boolean,
});

const breadcrumbsLinks = [
    {
        label: "Your Profile",
        route: {
            name: "profile.show",
            parameters: null,
        },
    },
    {
        label: "Profile Security",
    },
];
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head title="Profile Security" />
            <template #header>
                <h2 class="myPrimaryMainPageHeader">Profile Security</h2>
            </template>
            <template #description> Profile Security </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <LogoutOtherBrowserSessionsForm
                :sessions="sessions"
                :confirmsTwoFactorAuthentication="
                    confirmsTwoFactorAuthentication
                "
            />
            <SectionBorder />
            <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                <DeleteUserForm />
            </template>
        </LoggedInLayout>
    </MainLayout>
</template>
