<script setup>
import { Editor, useEditor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import { computed, onBeforeMount, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
import PageBuilder from "@/composables/PageBuilder";
import Link from "@tiptap/extension-link";
import { LinkIcon, ListBulletIcon } from "@heroicons/vue/24/outline";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";

const showModalUrl = ref(false);
const showModalTipTap = ref(false);
//
// use dynamic model
const typeModal = ref("");
const gridColumnModal = ref(Number(1));
const titleModal = ref("");
const descriptionModal = ref("");
const firstButtonModal = ref("");
const secondButtonModal = ref(null);
const thirdButtonModal = ref(null);
// set dynamic modal handle functions
const firstModalButtonFunction = ref(null);
const secondModalButtonFunction = ref(null);
const thirdModalButtonFunction = ref(null);

const store = useStore();

const pageBuilder = new PageBuilder(store);

const getElement = computed(() => {
    return store.getters["pageBuilderState/getElement"];
});
const textContentVueModel = ref("");

const textContent = computed(() => {
    return editor.value?.getHTML();
});

const getElementtextContentLength = ref(0);

const getElementOuterHTML = computed(() => {
    if (getElement.value === null) return;
    return getElement.value.outerHTML ? getElement.value.outerHTML : null;
});

watch(getElement, (newComponent) => {
    const tempContainer = document.createElement("div");
    tempContainer.innerHTML = newComponent;
    const textContent = tempContainer.textContent;
    getElementtextContentLength.value = textContent.length;
});

// Check if any of the child elements have the tagName "IMG"
const containsInvalidTags = ref(false);

const editor = useEditor({
    content: "",
    extensions: [
        StarterKit,
        Link.configure({
            openOnClick: false,
        }),
    ],
    editorProps: {
        attributes: {
            class: "prose-sm sm:prose-sm lg:prose-sm  m-5 focus:outline-none",
        },
    },
});

// watch for changes in textContent and update store and textContentVueModel
watch(textContent, (newValue) => {
    if (getElement.value) {
        store.commit("pageBuilderState/setTextAreaVueModel", newValue);
        if (
            typeof newValue === "string" &&
            newValue !== textContentVueModel.value
        ) {
            pageBuilder.handleTextInput(newValue);
        }
    }
});

watch(getElement, (getElementNewValue) => {
    if (editor.value) {
        // Get all child elements of the parentDiv
        const childElements = getElementNewValue?.children;
        if (getElementNewValue?.tagName === "IMG") {
            containsInvalidTags.value = true;
            return;
        }
        if (!childElements) return;
        Array.from(childElements).forEach((element) => {
            if (element?.tagName === "IMG" || element?.tagName === "DIV") {
                containsInvalidTags.value = true;
            } else {
                containsInvalidTags.value = false;
                editor.value.commands.setContent(getElementNewValue.innerHTML);
            }
        });
    }
});

const handleModalPreviewTiptap = function () {
    // set modal standards
    showModalTipTap.value = true;
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = "Manage Content";
    descriptionModal.value = null;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = null;

    // handle click
    firstModalButtonFunction.value = function () {
        console.log(`køre den 1`);
        // set open modal
        showModalTipTap.value = false;
    };

    // handle click
    secondModalButtonFunction.value = function () {};
};

// Manage URL
const previousUrl = ref(false);
const urlEnteret = ref("");
const urlError = ref();

const handleURL = function () {
    previousUrl.value = editor.value.getAttributes("link").href;
    urlEnteret.value = previousUrl.value;

    // set modal standards
    showModalUrl.value = true;
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = "Enter URL";
    descriptionModal.value = null;
    firstButtonModal.value = "Close";
    secondButtonModal.value = urlEnteret.value ? "Remove url" : null;
    thirdButtonModal.value = "Save";

    // handle click
    firstModalButtonFunction.value = function () {
        console.log(`køre den 2`);
        // set open modal
        showModalUrl.value = false;
    };

    // handle click
    secondModalButtonFunction.value = function () {
        editor.value.chain().focus().extendMarkRange("link").unsetLink().run();
        showModalUrl.value = false;
    };

    // handle click
    thirdModalButtonFunction.value = function () {
        const isNotValidated = validateURL();
        if (isNotValidated) {
            return;
        }
        if (!isNotValidated) {
            editor.value;
            setLink();
        }
        showModalUrl.value = false;
    };
    // end modal
};

//
//
const validateURL = function () {
    // initial value
    urlError.value = null;

    // url validation
    const urlRegex = /^https?:\/\//;
    const isValidURL = ref(true);
    isValidURL.value = urlRegex.test(urlEnteret.value);

    if (previousUrl.value) {
        urlEnteret.value = previousUrl.value;
    }

    // cancelled
    if (isValidURL.value === false) {
        urlError.value = "Invalid URL. Remember to add https://";
        return true;
    }

    return false;
};
const setLink = function () {
    console.log(" urlEnteret.value :", urlEnteret.value);
    // update link
    editor.value
        .chain()
        .focus()
        .extendMarkRange("link")
        .setLink({ href: urlEnteret.value })
        .run();
};

onBeforeMount(() => {
    editor.value?.destroy();
});

onMounted(() => {});
</script>
<template>
    <DynamicModal
        v-if="showModalUrl"
        :show="showModalUrl"
        :type="typeModal"
        :gridColumnAmount="gridColumnModal"
        :title="titleModal"
        :description="descriptionModal"
        :firstButtonText="firstButtonModal"
        :secondButtonText="secondButtonModal"
        :thirdButtonText="thirdButtonModal"
        @firstModalButtonFunction="firstModalButtonFunction"
        @secondModalButtonFunction="secondModalButtonFunction"
        @thirdModalButtonFunction="thirdModalButtonFunction"
    >
        <header></header>
        <main>
            <template v-if="!containsInvalidTags && editor">
                <div class="myInputGroup">
                    <label class="myPrimaryInputLabel" for="roles"
                        ><span>Enter URL</span></label
                    ><input
                        v-model="urlEnteret"
                        class="myPrimaryInput mt-1"
                        type="url"
                        placeholder="Enter url.."
                    />
                    <div
                        v-if="urlError"
                        class="min-h-[2.5rem] flex items-center justify-start"
                    >
                        <p
                            class="myPrimaryInputError mt-2 mb-0 py-0 self-start"
                        >
                            {{ urlError }}
                        </p>
                    </div>
                </div>
            </template>
        </main>
    </DynamicModal>

    <DynamicModal
        v-if="showModalTipTap && !showModalUrl"
        :show="showModalTipTap"
        maxWidth="5xl"
        :type="typeModal"
        :gridColumnAmount="gridColumnModal"
        :title="titleModal"
        :description="descriptionModal"
        :firstButtonText="firstButtonModal"
        :secondButtonText="secondButtonModal"
        :thirdButtonText="thirdButtonModal"
        @firstModalButtonFunction="firstModalButtonFunction"
        @secondModalButtonFunction="secondModalButtonFunction"
        @thirdModalButtonFunction="thirdModalButtonFunction"
    >
        <header></header>
        <main>
            <template v-if="!containsInvalidTags && editor">
                <div
                    class="relative xl:min-h-[45rem] xl:max-h-[45rem] lg:min-h-[40rem] lg:max-h-[40rem] md:min-h-[30rem] md:max-h-[30rem] min-h-[25rem] max-h-[25rem] overflow-y-scroll"
                >
                    <div
                        class="h-16 px-2 bg-black rounded-t-lg sticky top-0 z-20 flex gap-4 flex-shrink-0 justify-start items-center border-b-2 border-gray-100 overflow-x-scroll"
                    >
                        <button
                            @click="editor.chain().focus().setHardBreak().run()"
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                        >
                            <span class="material-symbols-outlined">
                                subdirectory_arrow_left
                            </span>
                        </button>
                        <button
                            @click="editor.chain().focus().toggleBold().run()"
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            :class="{
                                'bg-myPrimaryLinkColor text-white':
                                    editor.isActive('bold'),
                            }"
                        >
                            <span class="material-symbols-outlined">
                                format_bold
                            </span>
                        </button>
                        <button
                            @click="
                                editor
                                    .chain()
                                    .focus()
                                    .toggleHeading({ level: 2 })
                                    .run()
                            "
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            :class="{
                                'bg-myPrimaryLinkColor text-white':
                                    editor.isActive('heading', { level: 2 }),
                            }"
                        >
                            <span class="material-symbols-outlined">
                                format_h2
                            </span>
                        </button>
                        <button
                            @click="
                                editor
                                    .chain()
                                    .focus()
                                    .toggleHeading({ level: 3 })
                                    .run()
                            "
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            :class="{
                                'bg-myPrimaryLinkColor text-white':
                                    editor.isActive('heading', { level: 3 }),
                            }"
                        >
                            <span class="material-symbols-outlined">
                                format_h3
                            </span>
                        </button>
                        <button
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            @click="
                                editor.chain().focus().toggleBulletList().run()
                            "
                            :class="{
                                'bg-myPrimaryLinkColor text-white':
                                    editor.isActive('bulletList'),
                            }"
                        >
                            <span class="material-symbols-outlined">
                                list
                            </span>
                        </button>
                        <button
                            @click="handleURL"
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            :class="{
                                'bg-myPrimaryLinkColor text-white':
                                    editor.isActive('link'),
                            }"
                        >
                            <span class="material-symbols-outlined">
                                link
                            </span>
                        </button>
                    </div>
                    <editor-content
                        id="page-builder-editor"
                        :editor="editor"
                        class="myPrimaryTextArea p-0 m-0 rounded-lg border-none"
                    />
                </div>
            </template>
        </main>
    </DynamicModal>

    <div
        class="mt-2 mb-10 blockease-linear duration-200 block px-2 ease-linear"
    >
        <div class="flex items-center gap-2 my-2 bg-transparent py-2">
            <button
                @click="handleModalPreviewTiptap"
                type="button"
                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
            >
                <span class="material-symbols-outlined"> pinch_zoom_out </span>
            </button>
        </div>
        <template v-if="!containsInvalidTags && editor && !showModalTipTap">
            <div class="relative rounded-lg">
                <div
                    class="h-16 px-2 bg-black rounded-t-lg sticky top-0 z-20 flex gap-4 flex-shrink-0 justify-start items-center border-b-2 border-gray-100 overflow-x-scroll"
                >
                    <button
                        @click="editor.chain().focus().setHardBreak().run()"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                    >
                        <span class="material-symbols-outlined">
                            subdirectory_arrow_left
                        </span>
                    </button>
                    <button
                        @click="editor.chain().focus().toggleBold().run()"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                        :class="{
                            'bg-myPrimaryLinkColor text-white':
                                editor.isActive('bold'),
                        }"
                    >
                        <span class="material-symbols-outlined">
                            format_bold
                        </span>
                    </button>
                    <button
                        @click="
                            editor
                                .chain()
                                .focus()
                                .toggleHeading({ level: 2 })
                                .run()
                        "
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                        :class="{
                            'bg-myPrimaryLinkColor text-white': editor.isActive(
                                'heading',
                                { level: 2 }
                            ),
                        }"
                    >
                        <span class="material-symbols-outlined">
                            format_h2
                        </span>
                    </button>
                    <button
                        @click="
                            editor
                                .chain()
                                .focus()
                                .toggleHeading({ level: 3 })
                                .run()
                        "
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                        :class="{
                            'bg-myPrimaryLinkColor text-white': editor.isActive(
                                'heading',
                                { level: 3 }
                            ),
                        }"
                    >
                        <span class="material-symbols-outlined">
                            format_h3
                        </span>
                    </button>
                    <button
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                        @click="editor.chain().focus().toggleBulletList().run()"
                        :class="{
                            'bg-myPrimaryLinkColor text-white':
                                editor.isActive('bulletList'),
                        }"
                    >
                        <span class="material-symbols-outlined"> list </span>
                    </button>
                    <button
                        @click="handleURL"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                        :class="{
                            'bg-myPrimaryLinkColor text-white':
                                editor.isActive('link'),
                        }"
                    >
                        <span class="material-symbols-outlined"> link </span>
                    </button>
                </div>
                <editor-content
                    id="page-builder-editor"
                    :editor="editor"
                    class="myPrimaryTextArea p-0 m-0 rounded-lg border-none"
                />
            </div>
        </template>
    </div>
</template>
