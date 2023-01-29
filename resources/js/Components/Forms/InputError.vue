<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div class="min-h-[2rem] flex items-center justify-start">
        <p class="myPrimaryInputError my-0 py-0">
            {{ message }}
        </p>
    </div>
</template>
