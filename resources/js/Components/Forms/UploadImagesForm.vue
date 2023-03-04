<script setup>
import { ref } from "vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import { useForm } from "@inertiajs/vue3";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { v4 as uuidv4 } from "uuid";

console.log("se:", uuidv4());

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
const form = useForm({
    images: [],
    user_id: props.user.id,
    team: props.team,
});

const imagesPreview = ref([]);
const imagesInput = ref([]);
const isLoading = ref(false);

// update images preview
const loadBlob = (file) => {
    return new Promise((resolve, reject) => {
        const imageUploadId = uuidv4();
        const reader = new FileReader();
        reader.onload = (event) => {
            const imageUrl = event.target.result;
            form.images.push({
                image_upload_id: imageUploadId,
                file: file,
            });
            imagesPreview.value.push({
                preview_url: imageUrl,
                image_upload_id: imageUploadId,
            });
            resolve();
        };
        reader.onerror = (event) => {
            reject(event.target.error);
        };
        reader.readAsDataURL(file);
    });
};

const updateImagesPreview = async () => {
    console.log("images:", form.images);
    isLoading.value = true;

    if (imagesInput.value.length === 0) return;

    if (imagesInput.value.length !== 0) {
        const imagesPromise = Array.from(imagesInput.value.files).map(
            (image) => {
                console.log("image er:", image);
                return loadBlob(image);
            }
        );

        try {
            await Promise.all(imagesPromise); // wait for all images to be loaded
            isLoading.value = false;
        } catch (error) {
            isLoading.value = false;
            console.log("error uploading images:", error);
        }
    }
};

//
// submit
const submit = () => {
    form.post(route("media.store", [props.team]), {
        // errorBag: "updateProfileInformation",
        preserveScroll: true,
        onSuccess: () => {
            console.log("form submitted successfully");
        },
        onError: (err) => {
            console.log("form did not submit successfully:", err);
        },
        onFinish: () => {},
    });
};

const handleDeleteSingleImage = function (image_upload_id) {
    console.log("id er:", image_upload_id);
    // delete image form submitting and from images preview array

    const imageIndex = form.images.findIndex(
        (image) => image.image_upload_id === image_upload_id
    );
    if (imageIndex !== -1) {
        form.images.splice(imageIndex, 1);
    }
    const previewIndex = imagesPreview.value.findIndex(
        (preview) => preview.image_upload_id === image_upload_id
    );
    if (previewIndex !== -1) {
        imagesPreview.value.splice(previewIndex, 1);
    }
};
</script>

<template>
    <!-- image upload - start -->
    <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="myInputGroup">
            <p class="py-2">length for submit: {{ form.images.length }}</p>
            <p class="py-2">
                length of images preview array: {{ imagesPreview.length }}
            </p>
            <div class="col-span-3 mb-4">
                <label class="block text-sm font-medium text-gray-700"
                    >Upload images</label
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

            <InputError :message="form.errors?.images" />
        </div>

        <div v-if="isLoading === true" class="overflow-y-scroll max-h-80">
            <div class="text-center">
                <div role="status">
                    <svg
                        aria-hidden="true"
                        class="inline w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-myPrimaryBrandColor"
                        viewBox="0 0 100 101"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor"
                        />
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentFill"
                        />
                    </svg>
                </div>
            </div>
        </div>

        <div v-if="isLoading === false" class="overflow-y-scroll max-h-80">
            <div
                v-for="(image, index) in imagesPreview.length !== 0 &&
                imagesPreview"
                :key="index"
                class="py-2 my-1 border-b border-gray-200"
            >
                <div class="rounded flex-wrap flex item-center justify-between">
                    <div class="flex items-center gap-2">
                        <img
                            :src="image.preview_url"
                            alt="image"
                            class="w-14 rounded-xl"
                        />
                        <p class="myPrimaryParagraph text-xs py-2">
                            {{ image.fileName }}
                        </p>

                        <p
                            class="myPrimaryParagraph text-xs py-2 border-l border-gray-200 pl-2"
                        >
                            {{ image.fileSizeKB }} KB
                        </p>
                    </div>

                    <div
                        @click="handleDeleteSingleImage(image.image_upload_id)"
                        class="pl-2 pr-4 flex justify-left items-center cursor-pointer"
                    >
                        <span>
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

                <template
                    v-for="errorId in Object.keys(form.errors)"
                    :key="errorId"
                >
                    <span
                        class="pt-2 myPrimaryInputError"
                        v-if="errorId === image.image_upload_id"
                    >
                        Error: {{ form.errors[errorId] }}
                    </span>
                </template>
            </div>
        </div>

        <div v-if="isLoading === false">
            <SubmitButton
                :disabled="form.processing"
                buttonText="Upload Images"
            >
            </SubmitButton>
        </div>
    </form>
    <!-- image upload - end -->
</template>
