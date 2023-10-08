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

const breadcrumbsLinks = [
    {
        label: "Your Profile",
        route: {
            name: "profile.show",
            parameters: null,
        },
    },
    {
        label: "Update Profile",
    },
];
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head title="Update Profile" />
            <template #header>
                <h2 class="myPrimaryMainPageHeader">Update Profile</h2>
            </template>
            <template #description>Update Profile</template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <div>
                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <UpdateProfileInformationForm :user="$page.props.user" />
                </div>
            </div>
        </LoggedInLayout>
    </MainLayout>
</template>
