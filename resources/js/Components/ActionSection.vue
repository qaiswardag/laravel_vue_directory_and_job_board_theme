<script setup>
import SectionTitle from "./SectionTitle.vue";
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
                <div class="myPrimaryForm">
                    <div class="myPrimaryFormMain">
                        <slot name="main" />
                    </div>

                    <div class="myPrimaryFormSidebar">
                        <slot name="sidebar" />
                    </div>
                </div>
            </div>

            <div>
                <slot name="actions" />
            </div>
        </div>
    </div>
</template>
