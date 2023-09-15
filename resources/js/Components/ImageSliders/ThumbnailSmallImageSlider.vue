<script setup>
import {
    ArrowLeftCircleIcon,
    ArrowLeftIcon,
    ArrowRightCircleIcon,
    ArrowRightIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/outline";
import { computed, onMounted, ref, watch, watchEffect } from "vue";

const props = defineProps({
    images: {
        required: true,
    },
    imageSize: {
        required: true,
        type: String,
    },
    imageHeight: {
        type: String,
    },
    imageWidth: {
        required: true,
        type: String,
    },
    roundedFull: {
        required: true,
        type: Boolean,
    },
});

const currentImageIndex = ref(0);

const prevSlide = function () {
    currentImageIndex.value =
        (currentImageIndex.value - 1 + props.images.length) %
        props.images.length;
};

const nextSlide = function () {
    currentImageIndex.value =
        (currentImageIndex.value + 1) % props.images.length;
};

const sortedImages = computed(() => {
    // Find the primary image
    const primaryImage = props.images.find((image) => {
        return image?.pivot?.primary;
    });

    if (primaryImage) {
        // If a primary image is found, create a new array with the primary image first
        const otherImages = props.images.filter(
            (image) => image !== primaryImage
        );
        return [primaryImage, ...otherImages];
    }

    // If no primary image is found, return the original array
    return props.images;
});
</script>

<template>
    <div v-if="Array.isArray(images)" class="flex justify-center items-center">
        <div
            class="justify-center items-center flex-col"
            :class="[`${imageWidth}`]"
        >
            <div class="w-full flex justify-center">
                <!-- Carousel wrapper -->
                <template
                    v-for="(image, index) in Array.isArray(sortedImages) &&
                    sortedImages"
                    :key="image.id"
                >
                    <!-- image #start -->
                    <div
                        class="relative shrink-0 duration-200 ease-linear"
                        :class="[
                            `${imageHeight}`,
                            `${imageWidth}`,
                            { hidden: currentImageIndex !== index },
                            { 'rounded-full': roundedFull === true },
                        ]"
                    >
                        <img
                            :src="`/storage/uploads/${image[imageSize]}`"
                            alt="image"
                            class="object-cover"
                            :class="[
                                `${imageHeight}`,
                                `${imageWidth}`,
                                { 'rounded-full': roundedFull === true },
                            ]"
                        />
                    </div>
                    <!-- image #end -->
                </template>
                <!-- Slider controls -->
            </div>
            <div
                v-if="images.length >= 2"
                class="flex gap-4 items-center justify-center mt-2 z-30"
            >
                <button
                    type="button"
                    @click="prevSlide"
                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                >
                    <ChevronLeftIcon
                        class="shrink-0 h-4 w-4 m-2 stroke-2"
                    ></ChevronLeftIcon>
                </button>

                <button
                    type="button"
                    @click="nextSlide"
                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                >
                    <ChevronRightIcon
                        class="shrink-0 h-4 w-4 m-2 stroke-2"
                    ></ChevronRightIcon>
                </button>
            </div>
        </div>
    </div>
</template>
