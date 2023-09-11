<script setup>
import { XMarkIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";
import { useStore } from "vuex";

import ClassEditor from "@/Components/Builder/EditorMenu/Editables/ClassEditor.vue";
import ImageEditor from "@/Components/Builder/EditorMenu/Editables/ImageEditor.vue";
import ColorsEditor from "@/Components/Builder/EditorMenu/Editables/ColorEditor.vue";
import TextContent from "@/Components/Builder/EditorMenu/Editables/TextContent.vue";
import Typography from "@/Components/Builder/EditorMenu/Editables/Typography.vue";
import PaddingPlusMargin from "@/Components/Builder/EditorMenu/Editables/PaddingPlusMargin.vue";
import DeleteElement from "@/Components/Builder/EditorMenu/Editables/DeleteElement.vue";
import BorderRadius from "@/Components/Builder/EditorMenu/Editables/BorderRadius.vue";
import Borders from "@/Components/Builder/EditorMenu/Editables/Borders.vue";
import LinkEditor from "@/Components/Builder/EditorMenu/Editables/LinkEditor.vue";

// store
const store = useStore();
// emit
const emit = defineEmits(["closeEditor"]);

const props = defineProps({
    team: {
        required: true,
    },
    user: {
        required: true,
    },
});

// get current element tag
const getElement = computed(() => {
    return store.getters["designer/getElement"];
});
const getRestoredElement = computed(() => {
    return store.getters["designer/getRestoredElement"];
});

// Get tagName of element
const elementTag = computed(() => {
    return getElement.value?.tagName;
});

const isHeadingElement = computed(() => {
    return (
        (getElement.value instanceof HTMLElement &&
            getElement.value.innerText.trim() !== " ") ||
        getElement.value instanceof HTMLImageElement
    );
});
</script>

<template>
    <div class="h-full w-80 bg-white">
        <div class="h-screen flex flex-col">
            <div
                class="flex flex-row justify-between pt-2.5 pr-4 pl-4 items-center mb-3"
            >
                <div
                    @click="$emit('closeEditor')"
                    class="w-10 h-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                >
                    <XMarkIcon
                        class="shrink-0 h-4 w-4 m-2 stroke-2"
                    ></XMarkIcon>
                </div>
                <p class="font-bold text-sm">
                    Editing
                    <span class="lowercase">&lt;{{ elementTag }}&gt;</span>
                </p>
            </div>

            <div class="mb-4 overflow-y-scroll md:pb-24 pb-12">
                <div v-show="isHeadingElement === true">
                    <article>
                        <ImageEditor :user="user" :team="team"> </ImageEditor>
                    </article>
                    <article>
                        <TextContent></TextContent>
                    </article>
                    <article>
                        <LinkEditor></LinkEditor>
                    </article>
                    <article>
                        <Typography></Typography>
                    </article>
                    <article>
                        <ColorsEditor> </ColorsEditor>
                    </article>
                    <article>
                        <PaddingPlusMargin> </PaddingPlusMargin>
                    </article>
                    <article>
                        <BorderRadius></BorderRadius>
                    </article>
                    <article>
                        <Borders></Borders>
                    </article>
                    <article>
                        <ClassEditor></ClassEditor>
                    </article>
                </div>

                <article>
                    <div
                        v-show="
                            isHeadingElement === true ||
                            getRestoredElement !== null
                        "
                    >
                        <DeleteElement> </DeleteElement>
                    </div>
                </article>
                <article class="min-h-[20em]"></article>
            </div>
        </div>
    </div>
</template>
