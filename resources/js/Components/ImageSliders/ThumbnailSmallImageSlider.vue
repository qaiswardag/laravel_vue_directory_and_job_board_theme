<script setup>
import { computed, onMounted, ref } from "vue";

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
    imageClickable: {
        required: false,
        type: Boolean,
    },
    isLoading: {
        required: false,
    },
});

const emit = defineEmits(["firstButtonClick"]);
const firstButtonClick = function () {
    emit("firstButtonClick");
};

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

const showArrowsOnMounted = ref(false);

onMounted(() => {
    setTimeout(() => {
        showArrowsOnMounted.value = true;
    }, 500);
});
</script>

<template>
    <div
        loading="lazy"
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
                        id="imagePlaceholder"
                        class="relative shrink-0 duration-200 ease-linear rounded bg-white"
                        :class="[
                            imageHeight,
                            imageWidth,
                            { hidden: currentImageIndex !== index },
                            { 'rounded-full': roundedFull === true },
                        ]"
                    >
                        <img
                            @click="firstButtonClick"
                            class="object-cover rounded bg-white"
                            :src="`/storage/uploads/${image[imageSize]}`"
                            alt="image"
                            :class="[
                                imageHeight,
                                imageWidth,
                                { 'rounded-full': roundedFull === true },
                                { 'cursor-pointer': imageClickable === true },
                            ]"
                        />
                        <div
                            class="w-full relative shrink-0 duration-200 ease-linear rounded animate-pulse bg-red-300"
                        >
                            <div class="max-h-96 w-full"></div>
                        </div>
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
                    <span
                        v-if="showArrowsOnMounted"
                        class="material-symbols-outlined"
                    >
                        keyboard_arrow_left
                    </span>
                </button>

                <button
                    type="button"
                    @click="nextSlide"
                    class="bg-opacity-20 hover:bg-opacity-70 absolute cursor-pointer flex items-center justify-center bg-gray-50 aspect-square hover:bg-red-50 hover:text-gray-800"
                    :class="[
                        {
                            'rounded-none h-full top-0 right-0 bottom-0 w-8':
                                squareButtons,
                        },
                        {
                            'bg-opacity-60 top-1/2 bottom-1/2 right-[4px] shaddow h-10 w-10 rounded-full ':
                                !squareButtons,
                        },
                    ]"
                >
                    <span
                        v-if="showArrowsOnMounted"
                        class="material-symbols-outlined"
                    >
                        keyboard_arrow_right
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>
