<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import SearchBarWithOptions from "@/Components/SearchBars/SearchBarWithOptions.vue";
import { onMounted, ref } from "vue";

defineProps({
    user: {
        required: true,
    },
    userProfile: {
        required: true,
    },
});
</script>
<template>
    <GuestsLayout
        :title="`${userProfile?.first_name} ${userProfile?.last_name}`"
    >
        <template #header>
            <h2 class="myPrimaryMainPageHeader">
                La la this is user profile page
            </h2>
        </template>
        <div class="myPrimarySection">
            <p>This is view for specific user</p>
            <p>User first name is: {{ userProfile?.first_name }}</p>
            <p>User last name is: {{ userProfile?.last_name }}</p>
            <p class="mt-12">
                rest of user data: {{ JSON.stringify(userProfile) }}
            </p>
        </div>
    </GuestsLayout>
</template>
