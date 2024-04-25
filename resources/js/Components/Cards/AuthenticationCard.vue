<script setup>
defineProps({
    css: {
        type: String,
        required: false,
    },
});
</script>

<template>
    <div :class="css" class="flex flex-col sm:justify-center items-center">
        <div>
            <slot name="logo" />
        </div>

        <div
            class="w-full sm:max-w-md mt-6 bg-white mx.32 overflow-hidden rounded-md opacity-95 p-2"
        >
            <div
                class="border border-myPrimaryMediumGrayColor py-4 px-6 rounded-md"
            >
                <slot />
            </div>
        </div>
    </div>
</template>
