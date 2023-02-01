<script setup>
defineProps({
    on: Boolean,
    type: {
        required: true,
    },
});
</script>

<template>
    <div>
        <transition
            leave-active-class="transition ease-in duration-1000"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-show="on" class="text-sm text-gray-600">
                <div v-if="type === 'success'">
                    <div class="rounded-md bg-green-50 p-4">
                        <p class="text-sm font-medium text-green-800">
                            <slot />
                        </p>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
