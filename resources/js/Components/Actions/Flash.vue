<script setup>
import { CheckCircleIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import { computed, ref, watch } from "@vue/runtime-core";

const props = defineProps({
    flash: {
        required: true,
    },
});

const showFlash = ref(false);

const flashObject = computed(() => {
    return props.flash;
});

watch(flashObject, (newValue) => {
    if (newValue.error === null && newValue.success === null) {
        showFlash.value = null;
    }

    if (
        newValue.error !== null ||
        newValue.errorBags !== null ||
        newValue.success !== null
    ) {
        showFlash.value = true;
    }
});

// flash modal click
const flashModalButton = function () {
    showFlash.value = false;
};
</script>

<template>
    <div v-if="showFlash">
        <div v-if="flashObject.error">
            <div
                class="flex flex-col gap-4 bottom-6 right-0 fixed mb-8 md:mr-4 mx-2 z-50"
            >
                <div class="rounded-md bg-red-50 p-4 shadow-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <CheckCircleIcon
                                class="h-5 w-5 text-red-500"
                                aria-hidden="true"
                            />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                {{ flashObject.error }}
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button
                                    @click="flashModalButton"
                                    type="button"
                                    class="inline-flex rounded-md bg-red-50 p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-green-50"
                                >
                                    <span class="sr-only">Dismiss</span>
                                    <XMarkIcon
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="flashObject.success">
            <div
                class="flex flex-col gap-4 bottom-10 right-0 fixed mb-8 md:mr-4 mx-2 z-50"
            >
                <div
                    class="rounded-md bg-myPrimaryLightGrayColor p-4 shadow-lg"
                >
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <CheckCircleIcon
                                class="h-5 w-5 text-green-400"
                                aria-hidden="true"
                            />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ flashObject.success }}
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button
                                    @click="flashModalButton"
                                    type="button"
                                    class="inline-flex rounded-md bg-green-50 p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50"
                                >
                                    <span class="sr-only">Dismiss</span>
                                    <XMarkIcon
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
