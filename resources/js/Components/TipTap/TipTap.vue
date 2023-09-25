<script setup>
import { Editor, useEditor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import { computed, onBeforeMount, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
import PageBuilder from "@/composables/PageBuilder";
// import Bold from "@tiptap/extension-bold";
// import Document from "@tiptap/extension-document";
// import Paragraph from "@tiptap/extension-paragraph";
// import Text from "@tiptap/extension-text";
// import Heading from "@tiptap/extension-heading";
import Link from "@tiptap/extension-link";
import { LinkIcon, ListBulletIcon } from "@heroicons/vue/24/outline";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";

const store = useStore();
const pageBuilder = new PageBuilder(store);
// get current element
const getElement = computed(() => {
    return store.getters["pageBuilderState/getElement"];
});
const textContentVueModel = ref("Hello World");
const textContent = computed(() => {
    return editor.value?.getHTML();
});
//
//
const getElementtextContentLength = ref(0);

//
//
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
//
// Check if any of the child elements have the tagName "IMG"
const containsInvalidTags = ref(false);
//
//
//
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
            class: "prose dark:prose-invert prose-sm sm:prose-sm lg:prose-sm  m-5 focus:outline-none",
        },
    },

    //
    //
    //
    //
    // Register an event listener for the 'transaction' event
    onTransaction({ editor, transaction }) {
        // The editor state has changed.
    },
});
//
//
// watch for changes in textContent and update store and textContentVueModel
watch(textContent, (newValue) => {
    if (getElement.value) {
        store.commit("pageBuilderState/setTextAreaVueModel", newValue);
        if (
            typeof newValue === "string" &&
            newValue !== textContentVueModel.value
        ) {
            // TODO: FIX BELOW
            pageBuilder.handleTextInput(newValue);
        }
    }
});
//
//
//
//
watch(getElement, (newValue) => {
    if (editor.value) {
        // Get all child elements of the parentDiv
        const childElements = newValue?.children;
        if (newValue?.tagName === "IMG") {
            containsInvalidTags.value = true;
            return;
        }
        if (!childElements) return;
        Array.from(childElements).forEach((element) => {
            if (element?.tagName === "IMG" || element?.tagName === "DIV") {
                containsInvalidTags.value = true;
            } else {
                containsInvalidTags.value = false;
                editor.value.commands.setContent(newValue.innerHTML);
            }
        });
    }
});
//

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
// Manage URL
// Manage URL
// Manage URL
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
    descriptionModal.value = "Enter url";
    firstButtonModal.value = "Close";
    secondButtonModal.value = urlEnteret.value ? "Remove url" : null;
    thirdButtonModal.value = "Save";

    // handle click
    firstModalButtonFunction.value = function () {
        // set open modal
        showModalUrl.value = false;
    };
    //
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

//
//
//
//
//
//
//
//
//
//
//
//
//
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
    <template v-if="!containsInvalidTags">
        <div
            v-if="editor"
            class="my-12 blockease-linear duration-200 block px-4 ease-linear"
        >
            <div
                class="flex items-center gap-2 bg-black py-2 px-2 rounded-t-lg"
            >
                <button
                    @click="editor.chain().focus().toggleBold().run()"
                    class="text-base text-gray-200 font-semibold py-0.5 px-2 rounded-lg"
                    :class="{
                        'bg-myPrimaryLinkColor text-white':
                            editor.isActive('bold'),
                    }"
                >
                    B
                </button>
                <button
                    @click="
                        editor.chain().focus().toggleHeading({ level: 2 }).run()
                    "
                    class="text-base text-gray-200 font-semibold py-0.5 px-2 rounded-lg"
                    :class="{
                        'bg-myPrimaryLinkColor text-white': editor.isActive(
                            'heading',
                            { level: 2 }
                        ),
                    }"
                >
                    H2
                </button>
                <button
                    @click="
                        editor.chain().focus().toggleHeading({ level: 3 }).run()
                    "
                    class="text-base text-gray-200 font-semibold py-0.5 px-2 rounded-lg"
                    :class="{
                        'bg-myPrimaryLinkColor text-white': editor.isActive(
                            'heading',
                            { level: 3 }
                        ),
                    }"
                >
                    H3
                </button>
                <button
                    @click="editor.chain().focus().setHardBreak().run()"
                    class="text-base text-gray-200 font-semibold py-0.5 px-2 rounded-lg"
                >
                    br
                </button>
                <button
                    class="text-base text-gray-200 font-semibold py-1 px-1 rounded-lg"
                    @click="editor.chain().focus().toggleBulletList().run()"
                    :class="{
                        'bg-myPrimaryLinkColor text-white':
                            editor.isActive('bulletList'),
                    }"
                >
                    <ListBulletIcon class="w-5 h-5 stroke-2"></ListBulletIcon>
                </button>
                <button
                    @click="handleURL"
                    class="text-base text-gray-200 font-semibold py-1 px-1 rounded-lg"
                    :class="{
                        'bg-myPrimaryLinkColor text-white':
                            editor.isActive('link'),
                    }"
                >
                    <LinkIcon class="w-4 h-4 stroke-2"></LinkIcon>
                </button>
            </div>
            <editor-content
                :editor="editor"
                class="myPrimaryTextArea p-0 m-0 rounded-none rounded-b-lg"
            />
        </div>
    </template>
</template>
