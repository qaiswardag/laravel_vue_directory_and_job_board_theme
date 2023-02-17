<script setup>
import { XCircleIcon } from "@heroicons/vue/20/solid";
const props = defineProps({
    type: {
        required: true,
    },
});
</script>

<template>
    <div v-if="type === 'error'">
        <div class="rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        <slot />
                    </h3>
                </div>
            </div>
        </div>
    </div>
</template>
