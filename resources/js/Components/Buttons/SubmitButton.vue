<script setup>
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import SmallUniversalSpinner from "../Loaders/SmallUniversalSpinner.vue";
defineProps({
    type: {
        type: String,
        default: "submit",
    },
    buttonText: {
        type: String,
        required: true,
    },
    disabled: {
        type: Boolean,
        required: true,
    },
    onSuccess: {
        type: Boolean,
    },
    successMessage: {
        type: String,
    },
    hasErrors: {
        type: Boolean,
    },
    errorMessage: {
        type: String,
    },
    ButtonStyleDelete: {
        type: Boolean,
    },
});

const emit = defineEmits(["firstButtonClick"]);
// search anything modal button
const firstButtonClick = function () {
    emit("firstButtonClick");
};
</script>

<template>
    <div class="flex flex-col items-end justify-end gap-2">
        <div class="flex items-center gap-3">
            <Transition name="slide-fade">
                <div v-if="disabled" role="status">
                    <SmallUniversalSpinner></SmallUniversalSpinner>
                </div>
            </Transition>
            <button
                @click="firstButtonClick"
                :type="type"
                :disabled="disabled"
                class="myPrimaryButton myPrimaryGap min-w-[8rem]"
                :class="{
                    'opacity-25 cursor-default': disabled,
                    myPrimaryDeleteButton: ButtonStyleDelete,
                }"
            >
                <slot />
                {{ disabled ? "Loading.." : buttonText }}
            </button>
        </div>
    </div>
    <transition name="slide-fade">
        <template v-if="onSuccess">
            <ActionMessage :on="onSuccess" type="sucsess" class="ml-3">
                <div class="flex gap-1 items-center justify-end rounded">
                    {{ successMessage }}
                </div>
            </ActionMessage>
        </template>
    </transition>
</template>

<style scope>
/*
  Enter and leave animations can use different
  durations and timing functions.
*/
.slide-fade-enter-active {
    transition: all 0.5s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.5s cubic-bezier(1, 0.5, 0.8, 1);
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
