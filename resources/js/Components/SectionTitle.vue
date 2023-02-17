<template>
    <div class="myPrimaryFormTitleDescription">
        <h3 class="mySecondaryHeader">
            <slot name="title" />
        </h3>

        <p class="myPrimaryParagraph">
            <slot name="description" />
        </p>

        <div>
            <slot name="aside" />
        </div>
    </div>
</template>
