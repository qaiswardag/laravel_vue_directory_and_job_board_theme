<script setup>
import SectionTitle from "@/Components/Sections/SectionTitle.vue";

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
</script>

<template>
    <div class="myPrimarySection">
        <div>
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
                    class="myPrimaryContentLayout"
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
    </div>
</template>
