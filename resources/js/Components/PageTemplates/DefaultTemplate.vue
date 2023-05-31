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
            <div>
                <div class="text-center my-8">
                    <p class="text-3xl">
                        <slot name="title" />
                    </p>

                    <p class="myPrimaryParagraph">
                        <slot name="description" />
                    </p>
                </div>

                <slot name="header" />
                <div
                    :class="{ block: sidebarArea === false }"
                    class="myPrimaryForm"
                >
                    <div
                        :class="{ 'md:w-full': sidebarArea === false }"
                        class="md:w-8/12 flex flex-col myPrimaryGap bg-white px-4 py-4"
                    >
                        <slot name="main" />
                    </div>

                    <template v-if="sidebarArea === true">
                        <div
                            :class="{ 'md:w-full': sidebarArea === false }"
                            class="md:w-4/12 flex flex-col myPrimaryGap bg-white px-4 py-4"
                        >
                            <slot name="sidebar" />
                        </div>
                    </template>
                </div>

                <slot name="actions" />
            </div>
        </div>
    </div>
</template>
