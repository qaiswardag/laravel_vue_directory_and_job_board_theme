<script setup>
import { CheckCircleIcon } from "@heroicons/vue/20/solid";
defineProps({
    on: {
        required: true,
        Type: Boolean,
    },
    type: {
        required: true,
    },
});
</script>

<template>
    <div
        v-if="type === 'success'"
        class="flex flex-col gap-4 bottom-4 right-0 fixed mb-2 md:mr-0 mx-2"
    >
        <Transition v-show="on" name="slide-fade">
            <div class="rounded-3xl py-4 px-6 mx-2 bg-green-500 text-white">
                <div
                    class="flex gap-2 items-center justify-end myPrimaryParagraph text-white"
                >
                    <CheckCircleIcon class="h-5 w-5" aria-hidden="true" />

                    <slot />
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scope>
/*
  Enter and leave animations can use different
  durations and timing functions.
*/
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>
