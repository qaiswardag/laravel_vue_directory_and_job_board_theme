<template>
    <div
        class="md:col-span-1 flex mb-6 sm:mb-4 px-4 py-4 bg-gray-600 rounded border border-myPrimaryLightGrayColor"
    >
        <div>
            <h3 class="myTertiaryHeader text-white">
                <slot name="title" />
            </h3>

            <p class="myPrimaryParagraph text-white">
                <slot name="description" />
            </p>
        </div>

        <div>
            <slot name="aside" />
        </div>
    </div>
</template>
