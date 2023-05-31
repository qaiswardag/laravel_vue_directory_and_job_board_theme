<script setup>
import { computed, useSlots } from "vue";
import SectionTitle from "../Sections/SectionTitle.vue";

defineProps({
    sidebarArea: {
        Type: Boolean,
        default: true,
    },
    actionsArea: {
        Type: Boolean,
        default: true,
    },
});

defineEmits(["submitted"]);

const hasActions = computed(() => !!useSlots().actions);
</script>

<template>
    <div class="myPrimarySection">
        <form @submit.prevent="$emit('submitted')">
            <div class=""></div>

            <div
                :class="
                    hasActions
                        ? 'sm:rounded-tl-md sm:rounded-tr-md'
                        : 'sm:rounded-md'
                "
            >
                <div class="myPrimaryFormWithActions">
                    <SectionTitle>
                        <template #title>
                            <slot name="title" />
                        </template>
                        <template #description>
                            <slot name="description" />
                        </template>
                    </SectionTitle>

                    <slot name="header" />

                    <div
                        :class="{ block: sidebarArea === false }"
                        class="myPrimaryForm"
                    >
                        <div
                            :class="{ 'md:w-full': sidebarArea === false }"
                            class="myPrimaryFormMain"
                        >
                            <slot name="main" />
                        </div>

                        <template v-if="sidebarArea === true">
                            <div
                                :class="{ 'md:w-full': sidebarArea === false }"
                                class="myPrimaryFormSidebar"
                            >
                                <slot name="sidebar" />
                            </div>
                        </template>
                    </div>

                    <template v-if="actionsArea === true">
                        <div class="myPrimaryActions">
                            <slot name="actions" />
                        </div>
                    </template>
                </div>
            </div>
        </form>
    </div>
</template>
