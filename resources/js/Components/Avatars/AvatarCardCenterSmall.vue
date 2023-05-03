<script setup></script>
<template>
    <div class="flex flex-col justify-center items-center gap-2 mb-8 mt-4">
        <div
            v-if="
                $page.props.user && $page.props.user.profile_photo_path !== null
            "
            class="mt-2"
        >
            <img
                class="object-cover w-16 h-16 rounded-full"
                :src="`/storage/${$page.props.user.profile_photo_path}`"
                :alt="$page.props.user.first_name + $page.props.user.last_name"
            />
        </div>

        <div
            v-if="
                $page.props.user && $page.props.user.profile_photo_path === null
            "
            class="w-16 h-16 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-semibold text-white"
        >
            {{ $page.props.user.first_name.charAt(0).toUpperCase() }}
            {{ $page.props.user.last_name.charAt(0).toUpperCase() }}
        </div>
        <span class="flex flex-col items-center gap-1 myPrimaryParagraph">
            <span>
                {{ $page.props.user.first_name }}
                {{ $page.props.user.last_name }}
            </span>
            <span>
                {{ $page.props.user.email }}
            </span>
        </span>
    </div>
</template>
