<script setup>
const props = defineProps({
    jobs: {
        required: true,
    },
});
</script>
<template>
    <Head title="Jobs API" />

    {{ JSON.stringify(jobs) }}
</template>
