<script setup>
defineProps({
    type: {
        type: String,
        default: "button",
    },
});
</script>

<template>
    <button :type="type" class="myPrimaryDeleteButton">
        <slot />
    </button>
</template>
