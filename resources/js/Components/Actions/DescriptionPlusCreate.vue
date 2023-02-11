<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="md:flex md:justify-between md:items-center myPrimaryGap">
        <div>
            <slot name="title" />
        </div>
        <div class="flex md:flex-row flex-col md:items-center myPrimaryGap">
            <slot name="buttons" />
        </div>
    </div>
</template>
