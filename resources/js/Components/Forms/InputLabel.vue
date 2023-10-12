<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <label :value="value" class="myPrimaryInputLabel">
        {{ value }}
        <header>
            <slot></slot>
        </header>
    </label>
</template>
