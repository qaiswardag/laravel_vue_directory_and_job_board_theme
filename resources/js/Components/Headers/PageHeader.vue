<script setup>
defineProps({
    title: {
        Type: Boolean,
        required: true,
    },
    description: {
        default: false,
    },
    css: {
        default: false,
    },
});
</script>

<template>
    <div class="lg:pt-20 pt-12 lg:pb-0 pb-0" :class="css">
        <h1 class="myPrimaryHeader mb-0 text-center">
            {{ title }}
        </h1>
    </div>
</template>
