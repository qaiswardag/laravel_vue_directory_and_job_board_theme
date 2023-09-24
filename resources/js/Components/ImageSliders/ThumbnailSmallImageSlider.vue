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
    squareButtons: {
        required: false,
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
    <div
        v-if="Array.isArray(images)"
        class="relative flex justify-center items-center"
    >
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
                class="flex gap-[60%] items-center justify-center z-30"
            >
                <button
                    type="button"
                    @click="prevSlide"
                    class="bg-opacity-20 hover:bg-opacity-70 absolute cursor-pointer flex items-center justify-center bg-gray-50 aspect-square hover:bg-red-50 hover:text-gray-800"
                    :class="[
                        {
                            'rounded-none h-full top-0 bottom-0 left-0 w-8':
                                squareButtons,
                        },
                        {
                            'bg-opacity-60 top-1/2 bottom-1/2 left-[4px] shaddow h-10 w-10 rounded-full ':
                                !squareButtons,
                        },
                    ]"
                >
                    <ChevronLeftIcon
                        class="shrink-0 h-4 w-4 m-2 stroke-2"
                    ></ChevronLeftIcon>
                </button>

                <button
                    type="button"
                    @click="nextSlide"
                    class="bg-opacity-80 hover:bg-opacity-70 absolute cursor-pointer flex items-center justify-center bg-gray-50 aspect-square hover:bg-red-50 hover:text-gray-800"
                    :class="[
                        {
                            'rounded-none h-full top-0 right-0 bottom-0 w-8':
                                squareButtons,
                        },
                        {
                            'bg-opacity-80 top-1/2 bottom-1/2 right-[4px] shaddow h-10 w-10 rounded-full ':
                                !squareButtons,
                        },
                    ]"
                >
                    <ChevronRightIcon
                        class="shrink-0 h-4 w-4 m-2 stroke-2"
                    ></ChevronRightIcon>
                </button>
            </div>
        </div>
    </div>
</template>
