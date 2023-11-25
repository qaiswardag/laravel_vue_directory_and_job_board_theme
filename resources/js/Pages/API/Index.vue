<script setup>
import ApiTokenManager from "@/Pages/API/Partials/ApiTokenManager.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";

defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
});
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head title="API Tokens" />
            <template #header> API Tokens </template>

            <div>
                <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 px-3">
                    <ApiTokenManager
                        :tokens="tokens"
                        :available-permissions="availablePermissions"
                        :default-permissions="defaultPermissions"
                    />
                </div>
            </div>
        </LoggedInLayout>
    </MainLayout>
</template>
