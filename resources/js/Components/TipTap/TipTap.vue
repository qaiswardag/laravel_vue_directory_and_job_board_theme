<script setup>
import { useEditor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import { computed, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
import Designer from "@/composables/Designer";
import Bold from "@tiptap/extension-bold";
import Document from "@tiptap/extension-document";
import Paragraph from "@tiptap/extension-paragraph";
import Text from "@tiptap/extension-text";
import { LinkIcon, ListBulletIcon } from "@heroicons/vue/24/outline";
import Heading from "@tiptap/extension-heading";
import Link from "@tiptap/extension-link";

const store = useStore();
const designer = new Designer(store);

// get current element tag
const getElement = computed(() => {
    return store.getters["designer/getElement"];
});

const textContentVueModel = ref("Hello World");
const editor = useEditor({
    content: "TipTap",
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
});

const textContent = computed(() => {
    return editor.value?.getHTML();
});

// Watch for changes in textContent and update store and textContentVueModel
watch(textContent, (newValue) => {
    store.commit("designer/setTextAreaVueModel", newValue);

    if (
        typeof newValue === "string" &&
        newValue !== textContentVueModel.value
    ) {
        designer.handleTextInput(newValue);
    }
});

// Check if any of the child elements have the tagName "IMG"
const containsInvalidTags = ref(false);

// Get the div element you want to check

// Get the div element you want to check
const divElement = document.querySelector("div"); // Replace with your actual selector

// Watch for changes in textContent and update store and textContentVueModel
watch(getElement, (newValue) => {
    console.log("new element clicked");
    // Get all child elements of the parentDiv
    const childElements = newValue.children;

    if (newValue.tagName === "IMG") {
        containsInvalidTags.value = true;
        console.log("there is INVALID TAG");
        return;
    }

    Array.from(childElements).forEach((element) => {
        if (element.tagName === "IMG" || element.tagName === "DIV") {
            containsInvalidTags.value = true;
        } else {
            containsInvalidTags.value = false;
            editor.value?.commands?.setContent(newValue.innerHTML);
        }
    });
});

const setLink = function () {
    const previousUrl = editor.value.getAttributes("link").href;
    const url = window.prompt("URL", previousUrl);

    // cancelled
    if (url === null) {
        return;
    }

    // empty
    if (url === "") {
        editor.value.chain().focus().extendMarkRange("link").unsetLink().run();

        return;
    }

    // update link
    editor.value
        .chain()
        .focus()
        .extendMarkRange("link")
        .setLink({ href: url })
        .run();
};

onMounted(() => {});
</script>
<template>
    <template v-if="containsInvalidTags === false">
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
                        editor.chain().focus().toggleHeading({ level: 3 }).run()
                    "
                    class="text-base text-gray-200 font-semibold py-0.5 px-2 rounded-lg"
                    :class="{
                        'bg-myPrimaryLinkColor text-white':
                            editor.isActive('heading'),
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
                    @click="setLink"
                    class="text-base text-gray-200 font-semibold py-1 px-1 rounded-lg"
                    :class="{
                        'bg-myPrimaryLinkColor text-white':
                            editor.isActive('link'),
                    }"
                >
                    <LinkIcon class="w-5 h-5 stroke-2"></LinkIcon>
                </button>
            </div>
            <editor-content
                :editor="editor"
                class="myPrimaryTextArea p-0 m-0 rounded-none rounded-b-lg"
            />
        </div>
    </template>
</template>
