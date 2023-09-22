<script setup>
import { onMounted, ref } from "vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import { useForm } from "@inertiajs/vue3";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { v4 as uuidv4 } from "uuid";
import { usePromise } from "@/helpers/use-promise";
import { useStore } from "vuex";
import { TrashIcon } from "@heroicons/vue/24/outline";

// store
const store = useStore();

const emit = defineEmits(["uploadOnSuccess"]);

// first button function
const uploadOnSuccess = function () {
    store.commit("mediaLibrary/setCurrentPreviewImage", null);
    imagesPreview.value = [];
    imagesInput.value = [];
    emit("uploadOnSuccess");
};

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
const currentImagePreview = ref([]);
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
                file_name: file.name,
                file_size: (file.size / 1024).toFixed(2), // convert to KB
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
    isLoading.value = true;

    if (imagesInput.value.length === 0) return;

    if (imagesInput.value.length !== 0) {
        const imagesPromise = Array.from(imagesInput.value.files).map(
            (image) => {
                return loadBlob(image);
            }
        );

        try {
            // use timeout promise
            const promise = usePromise(1000);
            // wait for timeout
            await promise;
            // wait for all images to be loaded
            const imagesData = await Promise.all(imagesPromise);
            isLoading.value = false;
        } catch (error) {
            isLoading.value = false;
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
            uploadOnSuccess();
        },
        onError: (err) => {},
        onFinish: () => {},
    });
};

const handleDeleteSingleImage = function (image_upload_id) {
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

    store.commit("mediaLibrary/setCurrentPreviewImage", null);
};

const clickedImage = function (imageId) {
    currentImagePreview.value = imagesPreview.value.find(
        (image) => image.image_upload_id === imageId
    );

    store.commit(
        "mediaLibrary/setCurrentPreviewImage",
        currentImagePreview.value
    );
};

onMounted(() => {
    if (imagesPreview.value.length === 0) {
        store.commit("mediaLibrary/setCurrentPreviewImage", null);
    }

    imagesPreview.value = [];
    imagesInput.value = [];
});
</script>

<template>
    <!-- image upload - start -->
    <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="myInputGroup">
            <div class="col-span-3 mb-4">
                <label class="block text-sm font-normal text-gray-700"
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
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <InputLabel
                                class="text-myPrimaryBrandColor font-normal cursor-pointer"
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

        <div class="flex justify-start items-center">
            <p
                v-if="
                    Array.isArray(imagesPreview) && imagesPreview.length === 0
                "
                class="myPrimaryTag"
            >
                <span> Images 0 </span>
            </p>
            <p
                v-if="
                    Array.isArray(imagesPreview) && imagesPreview.length !== 0
                "
                class="myPrimaryTag"
            >
                <span>
                    Images {{ imagesPreview && imagesPreview.length }}
                </span>
            </p>
        </div>

        <div
            class="overflow-y-scroll md:min-h-[32rem] md:max-h-[32rem] min-h-[15rem] max-h-[15rem] p-2 border border-myPrimaryLightGrayColor"
        >
            <div v-if="isLoading === true">
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

            <div v-if="isLoading === false">
                <div
                    v-for="(image, index) in imagesPreview.length !== 0 &&
                    imagesPreview"
                    :key="index"
                    class="md:py-4 py-2 md:px-4 px-2 border-b border-gray-200 hover:bg-gray-50"
                >
                    <div
                        class="rounded flex-wrap flex items-center justify-between"
                    >
                        <div class="flex items-center gap-2">
                            <img
                                @click="clickedImage(image.image_upload_id)"
                                :src="image.preview_url"
                                alt="image"
                                class="h-14 w-14 object-cover rounded-md cursor-pointer"
                            />
                            <p class="myPrimaryParagraph text-xs py-2">
                                {{ image.file_name }}
                            </p>
                            <p
                                class="myPrimaryParagraph text-xs py-2 border-l border-gray-200 pl-2"
                            >
                                {{ image.file_size }} KB
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="
                                handleDeleteSingleImage(image.image_upload_id)
                            "
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                        >
                            <TrashIcon
                                class="shrink-0 w-4 h-4 m-2 stroke-2"
                            ></TrashIcon>
                        </button>
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
        </div>

        <div
            v-if="isLoading === false && imagesPreview.length !== 0"
            class="py-2 my-2"
        >
            <SubmitButton :disabled="form.processing" buttonText="Upload">
            </SubmitButton>
        </div>
    </form>
    <!-- image upload - end -->
</template>
