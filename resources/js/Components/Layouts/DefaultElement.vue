<script setup>
defineProps({
    descriptionArea: {
        Type: Boolean,
        required: true,
        default: true,
    },
});
</script>

<template>
    <!-- chimp start -->
    <div class="myPrimaryModule">
        <div class="myPrimaryContentSection">
            <h2 class="mySecondaryHeader">
                <slot name="title" />
            </h2>
            <p
                v-if="descriptionArea === true"
                class="myPrimaryParagraph font-medium"
            >
                <slot name="description" />
            </p>
        </div>
        <div class="myPrimaryContentSection">
            <slot name="content" />
        </div>
    </div>
</template>
