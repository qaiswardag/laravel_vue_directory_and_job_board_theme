<script setup>
import { Editor, useEditor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import { computed, onBeforeMount, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
import PageBuilder from "@/composables/PageBuilder";
import Link from "@tiptap/extension-link";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import TextColorEditor from "@/Components/PageBuilder/EditorMenu/Editables/TextColorEditor.vue";

const showModalUrl = ref(false);
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
    if (editor.value) {
        return editor.value.getHTML();
    }
});

const getElementtextContentLength = ref(0);

watch(getElement, (newVal) => {
    const tempContainer = document.createElement("div");

    if (newVal) {
        tempContainer.innerHTML = newVal;
        const textContent = tempContainer.textContent;
        getElementtextContentLength.value = textContent.length;
    }
});

// Check if any of the child elements have the tagName "IMG"

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
            class: "prose-sm sm:prose-sm lg:prose-sm focus:outline-none",
        },
    },
});

// watch for changes in textContent and update store and textContentVueModel
watch(textContent, (newValue) => {
    if (!pageBuilder.selectedElementIsValidText()) return;

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

// pageBuilder.selectedElementIsValidText(newValue);

const TipTapSetContent = function () {
    if (!pageBuilder.selectedElementIsValidText()) return;

    if (editor.value) {
        editor.value.commands.setContent(getElement.value.innerHTML);
    }
};

watch(getElement, () => {
    TipTapSetContent();
});

// Manage URL
const urlEnteret = ref("");
const newUpdatedExistingURL = ref("");
const urlError = ref();

watch(urlEnteret, (newVal) => {
    newUpdatedExistingURL.value = newVal;
});
const handleURL = function () {
    urlEnteret.value = editor.value.getAttributes("link").href;

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
        // set open modal
        showModalUrl.value = false;
        urlError.value = null;
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
            setEnteretURL();
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
    isValidURL.value = urlRegex.test(newUpdatedExistingURL.value);

    // cancelled
    if (isValidURL.value === false) {
        urlError.value =
            "The provided URL is invalid. Please ensure that it begins with 'https://' for proper formatting and security.";
        return true;
    }

    return false;
};
const setEnteretURL = function () {
    // update link
    editor.value
        .chain()
        .focus()
        .extendMarkRange("link")
        .setLink({ href: newUpdatedExistingURL.value })
        .run();
};

onBeforeMount(() => {
    editor.value?.destroy();
});

onMounted(() => {
    TipTapSetContent();
});
</script>
<template>
    <DynamicModal
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
            <div class="myInputGroup">
                <label class="myPrimaryInputLabel" for="roles"
                    ><span>Enter URL</span></label
                ><input
                    v-model="urlEnteret"
                    class="myPrimaryInput mt-1"
                    type="url"
                    placeholder="url"
                />
                <div
                    v-if="urlError"
                    class="min-h-[2.5rem] flex items-center justify-start"
                >
                    <p class="myPrimaryInputError mt-2 mb-0 py-0 self-start">
                        {{ urlError }}
                    </p>
                </div>
            </div>
        </main>
    </DynamicModal>

    <div
        class="mt-2 mb-10 blockease-linear duration-200 block px-2 ease-linear"
    >
        <div v-if="pageBuilder.selectedElementIsValidText() && editor">
            <div class="relative rounded-lg">
                <div
                    class="h-16 px-2 bg-black rounded-t-lg sticky top-0 z-10 flex gap-4 flex-shrink-0 justify-start items-center border-b-2 border-gray-100 overflow-x-scroll"
                >
                    <button
                        @click="editor.chain().focus().setHardBreak().run()"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                    >
                        <span class="material-symbols-outlined">
                            subdirectory_arrow_left
                        </span>
                    </button>
                    <button
                        @click="editor.chain().focus().toggleBold().run()"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
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
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
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
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
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
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
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
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
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
                    class="myPrimaryTextArea m-0 rounded-lg border-none lg:min-h-[20rem] lg:max-h-[40rem] md:min-h-[20rem] md:max-h-[20rem] min-h-[20rem] max-h-[20rem] overflow-y-scroll"
                />
            </div>
        </div>
    </div>
</template>
