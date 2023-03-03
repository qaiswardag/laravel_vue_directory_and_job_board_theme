<script setup>
import { ref } from "vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import { useForm } from "@inertiajs/vue3";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";

const selected = ref("Upload");

const tabs = [
    {
        name: "Upload",
        current: true,
    },
    {
        name: "Media library",
        current: false,
    },
    {
        name: "Unsplash",
        href: "#",
        current: false,
    },
];

const props = defineProps({
    team: {
        required: true,
    },
    user: {
        required: true,
    },
});

// form
const uploadImagesForm = useForm({
    images: [],
    user_id: props.user.id,
    team: props.team,
});

const imagesInput = ref([]);
const photosPreview = ref([]);
const isLoading = ref(false);

// update images preview
const loadImage = (blob) => {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                resolve(img);
            };
            img.onerror = (error) => {
                reject(error);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(blob);
    });
};

const loadBlob = (file) => {
    return new Promise((resolve, reject) => {
        const blob = new Blob([file], { type: file.type });
        loadImage(blob)
            .then((img) => {
                resolve(img.src);
            })
            .catch((error) => {
                reject(error);
            });
    });
};

const updateImagesPreview = async () => {
    isLoading.value = true;

    if (imagesInput.value.length === 0) return;

    if (imagesInput.value.length !== 0) {
        const imagesPromise = Array.from(imagesInput.value.files).map(
            (image) => {
                return loadBlob(image);
            }
        );

        try {
            const results = await Promise.all(imagesPromise);
            photosPreview.value = results;
            console.log("images for preview:", photosPreview.value);
            isLoading.value = false;
        } catch (error) {
            isLoading.value = false;
            console.log("error uploading images:", error);
        }
    }
};

// submit
const submit = () => {
    console.log("submit fn ran");

    uploadImagesForm.post(route("media.store"), {
        // errorBag: "updateProfileInformation",
        preserveScroll: true,
        onSuccess: () => {
            console.log("form submitted successfully");
        },
        onError: (err) => {
            console.log("form did not submit successfully. Error is:", err);
        },
        onFinish: () => {},
    });
};

const clearPreviewImages = () => {
    photosPreview.value = [];
};

const handleDeleteSingleImage = function (image) {
    console.log(
        "you clicked handle delete single image. unique image is:",
        image
    );
};
</script>

<template>
    <p class="my-4"></p>
    <p @click="clearPreviewImages" class="my-4 text-green-600 cursor-pointer">
        Click Me - clear images input
    </p>
    <p class="my-4">
        is loading er:
        {{ isLoading ? "true" : "false" }}
    </p>

    <!--  -->
    <!--  -->
    <!--  -->
    <!--  -->
    <!--  -->
    <!--  -->
    <!--  -->
    <!--  -->
    <!-- image upload - start -->
    <form @submit.prevent="submit" enctype="multipart/form-data">
        <div v-if="isLoading === false" class="myInputGroup">
            <div class="col-span-3">
                <label class="block text-sm font-medium text-gray-700"
                    >Cover photo</label
                >
                <div
                    class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-2 pb-2"
                >
                    <div class="space-y-1 text-center">
                        <svg
                            class="mx-auto h-8 w-8 text-gray-400"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 48 48"
                            aria-hidden="true"
                        >
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <InputLabel
                                class="text-myPrimaryBrandColor font-semibold cursor-pointer"
                                for="images"
                                value="Upload images"
                            />

                            <input
                                @change="updateImagesPreview()"
                                ref="imagesInput"
                                id="images"
                                type="file"
                                multiple
                                class="sr-only"
                            />

                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG or JPG</p>
                    </div>
                </div>
            </div>

            <InputError v-if="false" message="error" />
        </div>

        <!-- spinner start -->
        <div v-if="isLoading">
            <div class="text-center">
                <div class="flex items-center justify-center">
                    <div
                        class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                        role="status"
                    >
                        <span
                            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]"
                            >Loading...</span
                        >
                    </div>
                </div>
            </div>
        </div>
        <!-- spinner end -->

        <div
            class="overflow-y-scroll max-h-80 border-4 border-purple-600 p-2 m-2"
        >
            <div
                v-for="(image, index) in photosPreview.length !== 0 &&
                photosPreview"
                :key="index"
                class="my-2 border-2 border-black rounded flex item-center justify-between"
            >
                <img :src="image" alt="image" class="w-16" />
                <div
                    class="border-2 border-green-100 px-2 rounded flex justify-center items-center"
                >
                    <span
                        @click="handleDeleteSingleImage"
                        class="myPrimaryDeleteButtonNoBackground"
                        >Delete</span
                    >
                </div>
            </div>
        </div>

        <SubmitButton
            :disabled="uploadImagesForm.processing"
            buttonText="Upload"
        >
        </SubmitButton>
    </form>
    <!-- image upload - end -->
</template>
