<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div v-show="message">
        <p class="myPrimaryInputError">
            {{ message }}
        </p>
    </div>
</template>
