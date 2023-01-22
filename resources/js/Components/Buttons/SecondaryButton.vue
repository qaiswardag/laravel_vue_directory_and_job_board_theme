<script setup>
defineProps({
    type: {
        type: String,
        default: "button",
    },
});
</script>

<template>
    <button :type="type" class="mySecondaryButton">
        <slot />
    </button>
</template>
