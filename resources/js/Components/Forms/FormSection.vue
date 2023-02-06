<script setup>
import { computed, useSlots } from "vue";
import SectionTitle from "../SectionTitle.vue";

defineEmits(["submitted"]);

const hasActions = computed(() => !!useSlots().actions);
</script>

<template>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <SectionTitle>
            <template #title>
                <slot name="title" />
            </template>
            <template #description>
                <slot name="description" />
            </template>
        </SectionTitle>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <form
                class="border border-gray-300 rounded"
                @submit.prevent="$emit('submitted')"
            >
                <div
                    class="px-4 py-5 bg-white sm:p-6"
                    :class="
                        hasActions
                            ? 'sm:rounded-tl-md sm:rounded-tr-md'
                            : 'sm:rounded-md'
                    "
                >
                    <slot name="form" />
                </div>

                <div
                    v-if="hasActions"
                    class="px-4 pt-3 pb-1 bg-gray-100 sm:px-6 min-h-[5.5rem] text-right sm:rounded-bl-md sm:rounded-br-md"
                >
                    <slot name="actions" />
                </div>
            </form>
        </div>
    </div>
</template>
