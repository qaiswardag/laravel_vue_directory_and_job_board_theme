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
    users: {
        required: true,
    },
});
</script>
<template>
    <GuestsLayout title="Posts">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Users</h2>
        </template>
        <div class="myPrimarySection">
            <h1 class="my-8 text-4xl text-center">Users</h1>
            <div v-for="user in users.data" :key="user.id">
                <div class="my-12 mx-24 border-2 border-red-400 p-4">
                    <p class="my-4 mb-8">user: {{ user }}</p>
                    <Link
                        class="myPrimaryLink text-2xl font-normal mt-4"
                        :href="
                            route(
                                'users.show',
                                encodeURIComponent(user.username.toLowerCase())
                            )
                        "
                    >
                        {{ user.first_name }} {{ user.last_name }}
                    </Link>

                    <p class="my-8">
                        Public Status: {{ user.public ? "Public" : "Private" }}
                    </p>
                    <p class="my-4">user id is: {{ user.id }}</p>
                </div>
            </div>
        </div>
        <Pagination :links="users.links"></Pagination>
    </GuestsLayout>
</template>
