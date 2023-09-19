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
        <slot name="title" />
        <slot name="description" />
        <slot name="header" />

        <div
            :class="{ block: sidebarArea === false }"
            class="myPrimaryContentLayout"
        >
            <article
                :class="{ 'md:w-full': sidebarArea === false }"
                class="md:w-8/12 flex flex-col myPrimaryGap px-4"
            >
                <slot name="main" />
            </article>

            <template v-if="sidebarArea === true">
                <aside
                    :class="{ 'md:w-full': sidebarArea === false }"
                    class="md:w-4/12 flex flex-col myPrimaryGap bg-white px-4 py-4"
                >
                    <slot name="sidebar" />
                </aside>
            </template>
        </div>

        <slot name="actions" />
    </div>
</template>
