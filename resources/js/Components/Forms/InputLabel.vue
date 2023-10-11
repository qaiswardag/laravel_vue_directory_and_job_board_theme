<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <label :value="value" class="myPrimaryInputLabel">
        {{ value }}
        <header>
            <slot name="header"></slot>
        </header>
    </label>
</template>
