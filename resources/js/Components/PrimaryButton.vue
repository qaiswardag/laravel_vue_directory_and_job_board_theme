<script setup>
defineProps({
    type: {
        type: String,
        default: "submit",
    },
});
</script>

<template>
    <button :type="type" class="myPrimaryButton">
        <slot />
    </button>
</template>
