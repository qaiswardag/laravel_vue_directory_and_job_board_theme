<script setup>
import SectionTitle from "./SectionTitle.vue";
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
            <div class="border border-gray-300 rounded">
                <div
                    class="px-4 py-5 bg-white sm:p-6 sm:rounded-tl-md sm:rounded-tr-m overflow-x-scroll"
                >
                    <slot name="content" />
                </div>

                <div
                    class="px-4 py-4 bg-gray-100 sm:px-6 text-right sm:rounded-bl-md sm:rounded-br-md"
                >
                    <slot name="actions" />
                </div>
            </div>
        </div>
    </div>
</template>
