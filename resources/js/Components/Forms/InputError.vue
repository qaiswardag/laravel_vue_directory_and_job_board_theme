<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div class="min-h-[1.5rem] flex items-center justify-start">
        <p class="myPrimaryInputError mt-2 mb-0 py-0 self-start">
            {{ message }}
        </p>
    </div>
</template>
