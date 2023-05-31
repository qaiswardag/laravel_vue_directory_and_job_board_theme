<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import ActionSection from "@/Components/Actions/ActionSection.vue";
import Checkbox from "@/Components/Forms/Checkbox.vue";
import DangerButton from "@/Components/Buttons/DangerButton.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import SecondaryButton from "@/Components/Buttons/SecondaryButton.vue";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";
import TextInput from "@/Components/Forms/TextInput.vue";

const props = defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
});

const createApiTokenForm = useForm({
    name: "",
    permissions: props.defaultPermissions,
});

const updateApiTokenForm = useForm({
    permissions: [],
});

const deleteApiTokenForm = useForm({});

const displayingToken = ref(false);
const managingPermissionsFor = ref(null);
const apiTokenBeingDeleted = ref(null);

const createApiToken = () => {
    createApiTokenForm.post(route("api-tokens.store"), {
        preserveScroll: true,
        onSuccess: () => {
            displayingToken.value = true;
            createApiTokenForm.reset();
        },
    });
};

const manageApiTokenPermissions = (token) => {
    updateApiTokenForm.permissions = token.abilities;
    managingPermissionsFor.value = token;
};

const updateApiToken = () => {
    updateApiTokenForm.put(
        route("api-tokens.update", managingPermissionsFor.value),
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => (managingPermissionsFor.value = null),
        }
    );
};

const confirmApiTokenDeletion = (token) => {
    apiTokenBeingDeleted.value = token;
};

const deleteApiToken = () => {
    deleteApiTokenForm.delete(
        route("api-tokens.destroy", apiTokenBeingDeleted.value),
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => (apiTokenBeingDeleted.value = null),
        }
    );
};
</script>

<template>
    <div>
        <!-- Generate API Token -->
        <FormSection @submitted="createApiToken">
            <template #title> Create API Token </template>

            <template #description>
                API tokens allow third-party services to authenticate with our
                application on your behalf.
            </template>

            <template #main>
                <!-- Token Name -->
                <div class="myInputGroup">
                    <InputLabel for="name" value="Name" />
                    <TextInput
                        id="name"
                        v-model="createApiTokenForm.name"
                        type="text"
                        autofocus
                    />
                    <InputError :message="createApiTokenForm.errors.name" />
                </div>

                <!-- Token Permissions -->
                <div v-if="availablePermissions.length > 0" class="col-span-6">
                    <InputLabel for="permissions" value="Permissions" />

                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div
                            v-for="permission in availablePermissions"
                            :key="permission"
                        >
                            <label class="flex items-center">
                                <Checkbox
                                    v-model:checked="
                                        createApiTokenForm.permissions
                                    "
                                    :value="permission"
                                />
                                <span
                                    class="ml-2 text-sm text-myPrimaryDarkGrayColor"
                                    >{{ permission }}</span
                                >
                            </label>
                        </div>
                    </div>
                </div>
            </template>

            <template #actions>
                <ActionMessage :on="createApiTokenForm.recentlySuccessful">
                    Created.
                </ActionMessage>

                <PrimaryButton
                    :class="{ 'opacity-25': createApiTokenForm.processing }"
                    :disabled="createApiTokenForm.processing"
                >
                    Create
                </PrimaryButton>
            </template>
        </FormSection>

        <div v-if="tokens.length > 0">
            <SectionBorder />

            <!-- Manage API Tokens -->
            <div class="mt-10 sm:mt-0">
                <ActionSection>
                    <template #title> Manage API Tokens </template>

                    <template #description>
                        You may delete any of your existing tokens if they are
                        no longer needed.
                    </template>

                    <!-- API Token List -->
                    <template #main>
                        <div class="space-y-6">
                            <div
                                v-for="token in tokens"
                                :key="token.id"
                                class="flex items-center justify-between"
                            >
                                <div class="break-all">
                                    {{ token.name }}
                                </div>

                                <div class="flex items-center ml-2">
                                    <div
                                        v-if="token.last_used_ago"
                                        class="text-sm text-myPrimaryLightTextColor"
                                    >
                                        Last used {{ token.last_used_ago }}
                                    </div>

                                    <button
                                        v-if="availablePermissions.length > 0"
                                        class="cursor-pointer ml-6 text-sm text-myPrimaryLightTextColor underline"
                                        @click="
                                            manageApiTokenPermissions(token)
                                        "
                                    >
                                        Permissions
                                    </button>

                                    <button
                                        class="cursor-pointer ml-6 text-sm text-red-500"
                                        @click="confirmApiTokenDeletion(token)"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </ActionSection>
            </div>
        </div>

        <!-- Token Show Modal here -->
        <div>
            Please copy your new API token. For your security, it won't be shown
            again.
        </div>

        <div
            v-if="$page.props.jetstream.flash.token"
            class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-myPrimaryDarkGrayColor break-all"
        >
            {{ $page.props.jetstream.flash.token }}
        </div>

        <SecondaryButton @click="displayingToken = false">
            Close
        </SecondaryButton>
        <!-- Token Show Modal here -->

        <!-- Token Show Modal here below -->
        <!-- API Token Permissions Modal -->

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="permission in availablePermissions" :key="permission">
                <label class="flex items-center">
                    <Checkbox
                        v-model:checked="updateApiTokenForm.permissions"
                        :value="permission"
                    />
                    <span class="ml-2 text-sm text-myPrimaryDarkGrayColor">{{
                        permission
                    }}</span>
                </label>
            </div>
        </div>

        <SecondaryButton @click="managingPermissionsFor = null">
            Cancel
        </SecondaryButton>

        <PrimaryButton
            class="ml-3"
            :class="{ 'opacity-25': updateApiTokenForm.processing }"
            :disabled="updateApiTokenForm.processing"
            @click="updateApiToken"
        >
            Save
        </PrimaryButton>

        <!-- API Token Permissions Modal -->
        <!-- Token Show Modal here belaboveow -->

        <!-- PUT BELOW INSIDE A MODAL -->
        <SecondaryButton @click="apiTokenBeingDeleted = null">
            Cancel
        </SecondaryButton>

        <DangerButton
            class="ml-3"
            :class="{ 'opacity-25': deleteApiTokenForm.processing }"
            :disabled="deleteApiTokenForm.processing"
            @click="deleteApiToken"
        >
            Delete
        </DangerButton>
        <!-- PUT ABOVE INSIDE A MODAL -->
    </div>
</template>
