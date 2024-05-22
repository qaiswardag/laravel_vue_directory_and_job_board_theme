<script setup>
import { onMounted, ref } from "vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import { useForm } from "@inertiajs/vue3";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { v4 as uuidv4 } from "uuid";
import { usePromise } from "@/helpers/use-promise";
import { useStore } from "vuex";
import { PhotoIcon, TrashIcon } from "@heroicons/vue/24/outline";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";

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
    forUserNotTeam: {
        required: true,
        type: Boolean,
    },
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
            await Promise.all(imagesPromise);
            isLoading.value = false;
        } catch (error) {
            isLoading.value = false;
        }
    }
};

//
// submit
const submit = () => {
    if (props.forUserNotTeam) {
        form.post(route("user.media.store"), {
            preserveScroll: true,
            onSuccess: () => {
                uploadOnSuccess();
            },
            onError: (err) => {},
            onFinish: () => {},
        });

        return;
    }

    form.post(route("media.store", { id: props.team.id }), {
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
                <div class="relativeflex flex-col items-center justify-center">
                    <InputLabel
                        class="myPrimaryParagraph rounded-full bg-myPrimaryLightGrayColor text-center w-full inset-0 block text-base cursor-pointer pt-6 pb-6 px-4"
                        for="images"
                        value="Click & Upload multiple images.."
                    >
                        <div
                            class="myPrimaryButton mt-4 hover:shadow hover:outline hover:outline-myPrimaryLinkColor hover:outline-offset-2 gap-3"
                        >
                            <span class="material-symbols-outlined">
                                cloud_upload
                            </span>
                            <span> PNG, JPG, up to 2MB </span>
                        </div>
                    </InputLabel>

                    <input
                        @change="updateImagesPreview()"
                        ref="imagesInput"
                        id="images"
                        type="file"
                        multiple
                        class="sr-only"
                    />
                </div>
            </div>

            <InputError :message="form.errors?.images" />
        </div>

        <!-- Actions # start -->
        <div
            v-if="isLoading === false && imagesPreview.length !== 0"
            class="flex justify-center gap-2 items-center mb-4"
        >
            <div
                v-if="
                    Array.isArray(imagesPreview) && imagesPreview.length === 0
                "
                class="myPrimaryTag"
            >
                <span class="font-medium"> Images 0 </span>
            </div>
            <div
                v-if="
                    Array.isArray(imagesPreview) && imagesPreview.length !== 0
                "
                class="myPrimaryTag"
            >
                <div class="font-medium">
                    Images {{ imagesPreview && imagesPreview.length }}
                </div>
            </div>

            <button @click="submit" type="button" class="myPrimaryButton">
                <span class="material-symbols-outlined"> cloud_upload </span>
                <span> Upload Images </span>
            </button>
        </div>
        <!-- Actions # end -->

        <div
            class="rounded p-2 border border-myPrimaryLightGrayColor overflow-y-scroll md:min-h-[19.5rem] md:max-h-[19.5rem] min-h-[15rem] max-h-[15rem]"
        >
            <div v-if="isLoading === true">
                <div class="flex items-center justify-center">
                    <SmallUniversalSpinner
                        class="h-40"
                        width="w-6"
                        height="h-6"
                        border="border-4"
                    ></SmallUniversalSpinner>
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
                                class="w-16 object-cover rounded-sm cursor-pointer"
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
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                        >
                            <span class="material-symbols-outlined">
                                delete
                            </span>
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
    </form>
    <!-- image upload - end -->
</template>
