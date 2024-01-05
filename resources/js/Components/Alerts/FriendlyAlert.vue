<script setup>
defineProps({
    message: {
        required: false,
    },
});
</script>

<template>
    <template v-if="message">
        <div
            class="flex items-center gap-4 bg-myPrimaryLightGrayColor px-4 py-3 rounded-full lg:w-2/3 mx-auto shadow w-full shadow"
            role="alert"
        >
            <span class="material-symbols-outlined"> wb_incandescent </span>
            <p class="myPrimaryParagraph font-medium">
                {{ message }}
            </p>
        </div>
    </template>

    <slot></slot>
</template>
