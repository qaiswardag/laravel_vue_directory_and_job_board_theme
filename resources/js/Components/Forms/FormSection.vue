<script setup>
import { computed, useSlots } from "vue";
import SectionTitle from "../SectionTitle.vue";

defineProps({
    noSidebar: {
        Type: Boolean,
    },
});

defineEmits(["submitted"]);

const hasActions = computed(() => !!useSlots().actions);
</script>

<template>
    <div class="mt-5 md:mt-0">
        <form @submit.prevent="$emit('submitted')">
            <div class=""></div>

            <div
                :class="
                    hasActions
                        ? 'sm:rounded-tl-md sm:rounded-tr-md'
                        : 'sm:rounded-md'
                "
            >
                <SectionTitle>
                    <template #title>
                        <slot name="title" />
                    </template>
                    <template #description>
                        <slot name="description" />
                    </template>
                </SectionTitle>

                <slot name="header" />

                <div class="flex flex-col myPrimaryGap">
                    <div :class="{ block: noSidebar }" class="myPrimaryForm">
                        <div
                            :class="{ 'md:w-full': noSidebar }"
                            class="myPrimaryFormMain"
                        >
                            <slot name="main" />
                        </div>
                        <div
                            :class="{ 'md:w-full': noSidebar }"
                            class="myPrimaryFormSidebar"
                        >
                            <slot name="sidebar" />
                        </div>
                    </div>

                    <slot name="actions" />
                </div>
            </div>
        </form>
    </div>
</template>
