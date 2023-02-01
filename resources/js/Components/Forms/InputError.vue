<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div class="min-h-[2.5rem] flex items-center justify-start">
        <p class="myPrimaryInputError my-0 py-0 self-start">
            {{ message }}
        </p>
    </div>
</template>
