<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <label class="myPrimaryInputLabel">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
