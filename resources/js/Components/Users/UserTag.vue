<script setup>
const props = defineProps({
    user: {
        required: true,
    },
    showTeamRole: {
        required: false,
    },
    currentUserTeamRole: {
        required: false,
    },
    clickable: {
        required: false,
    },
});
</script>

<template>
    <div
        v-if="user"
        :class="{
            'hover:bg-myPrimaryLinkColor hover:text-white cursor-pointer':
                clickable,
        }"
        class="myPrimaryTag inline-block py-2"
    >
        <div class="flex items-center gap-2">
            <div v-if="user.profile_photo_path" class="flex-shrink-0">
                <img
                    class="object-cover h-8 w-8 rounded-full"
                    :src="`/storage/${user.profile_photo_path}`"
                    alt="User Image"
                />
            </div>

            <div
                v-if="!user.profile_photo_path"
                class="flex-shrink-0 h-8 w-8 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs text-white"
            >
                {{ user.first_name.charAt(0).toUpperCase() }}
                {{ user.last_name.charAt(0).toUpperCase() }}
            </div>
            <div>
                <p class="text-xs font-medium">
                    {{ user.first_name }}
                    {{ user.last_name }}
                </p>

                <template v-if="showTeamRole && currentUserTeamRole">
                    <p class="text-xs font-normal">
                        Role:
                        {{
                            currentUserTeamRole?.key
                                ? currentUserTeamRole.key
                                : null
                        }}
                        {{
                            currentUserTeamRole?.role
                                ? currentUserTeamRole.role
                                : null
                        }}
                    </p>
                </template>
            </div>
        </div>
    </div>
    <!-- user photo - end -->
    <div v-if="!user" class="myPrimaryTag inline-block py-2">Unknown</div>
</template>
