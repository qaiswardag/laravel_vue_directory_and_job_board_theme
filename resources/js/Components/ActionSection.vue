<script setup>
import SectionTitle from "./SectionTitle.vue";

defineProps({
    noSidebar: {
        Type: Boolean,
    },
});
</script>

<template>
    <div class="mt-5 md:mt-0">
        <div>
            <div class="sm:rounded-tl-md sm:rounded-tr-m overflow-x-scroll">
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
        </div>
    </div>
</template>
