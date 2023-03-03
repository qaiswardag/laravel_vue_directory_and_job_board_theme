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
            uploadImagesForm.images = photosPreview.value;
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
    uploadImagesForm.post(route("media.store", [props.team]), {
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

const handleDeleteSingleImage = function (index) {
    // delete this image from submitting
    photosPreview.value.splice(index, 1);
    uploadImagesForm.images = photosPreview.value;
};
</script>

<template>
    <!-- image upload - start -->
    <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="myInputGroup">
            <div class="col-span-3 mb-4">
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

            <InputError :message="uploadImagesForm.errors.images" />
        </div>

        <div class="overflow-y-scroll max-h-80">
            <div
                v-for="(image, index) in photosPreview.length !== 0 &&
                photosPreview"
                :key="index"
                class="rounded flex item-center justify-between py-2 my-4 border-b border-gray-200"
            >
                <img :src="image" alt="image" class="w-14 rounded-xl" />
                <div class="pl-2 pr-4 flex justify-left items-center">
                    <span @click="handleDeleteSingleImage(index)">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5 text-myErrorColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                            />
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <SubmitButton
            :disabled="isLoading || uploadImagesForm.processing"
            buttonText="Upload"
        >
        </SubmitButton>
    </form>
    <!-- image upload - end -->
</template>
