<script setup>
import { CheckCircleIcon } from "@heroicons/vue/20/solid";
defineProps({
    on: { required: true, Boolean },
    type: {
        required: true,
    },
});
</script>

<template>
    <transition name="slide-fade">
        <div v-show="on" class="text-sm text-myPrimaryNormalColor mt-2">
            <div v-if="type === 'sucsess'">
                <div class="flex gap-1 items-center justify-end rounded">
                    <CheckCircleIcon
                        class="h-5 w-5 text-green-400"
                        aria-hidden="true"
                    />
                    <p class="myPrimaryParagraph">
                        <slot />
                    </p>
                </div>
            </div>
        </div>
    </transition>
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

.bounce-enter-active {
    animation: bounce-in 0.5s;
}
.bounce-leave-active {
    animation: bounce-in 0.5s reverse;
}
@keyframes bounce-in {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.25);
    }
    100% {
        transform: scale(1);
    }
}
</style>
