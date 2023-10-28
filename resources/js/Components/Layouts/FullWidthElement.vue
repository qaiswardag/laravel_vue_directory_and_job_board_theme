<script setup>
defineProps({
    descriptionArea: {
        Type: Boolean,
        required: true,
        default: true,
    },
    headerArea: {
        Type: Boolean,
        default: true,
    },
});
</script>

<template>
    <!-- chimp start -->
    <div class="myPrimaryWidthScreenModule">
        <template v-if="headerArea">
            <div class="myHeaderParagraphSection">
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
        </template>
        <div class="myPrimaryContentSection">
            <slot name="content" />
        </div>
    </div>
</template>
