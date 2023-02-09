<template>
    <div class="md:col-span-1 flex justify-between mb-4">
        <div>
            <h3 class="myPrimayHeader">
                <slot name="title" />
            </h3>

            <p class="myPrimaryParagraph">
                <slot name="description" />
            </p>
        </div>

        <div>
            <slot name="aside" />
        </div>
    </div>
</template>
