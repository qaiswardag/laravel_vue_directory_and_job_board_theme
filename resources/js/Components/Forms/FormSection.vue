<script setup>
import { computed, useSlots } from "vue";
import SectionTitle from "../SectionTitle.vue";

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
                <div class="myPrimaryForm">
                    <div class="myPrimaryFormMain">
                        <slot name="main" />
                    </div>
                    <div class="myPrimaryFormSidebar">
                        <slot name="sidebar" />
                    </div>
                </div>

                <div>
                    <slot name="actions" />
                </div>
            </div>
        </form>
    </div>
</template>
