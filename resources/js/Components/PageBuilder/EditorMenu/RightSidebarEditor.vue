<script setup>
import { XMarkIcon } from "@heroicons/vue/24/outline";
import { computed, ref, watch } from "vue";
import { useStore } from "vuex";

import ClassEditor from "@/Components/PageBuilder/EditorMenu/Editables/ClassEditor.vue";
import ImageEditor from "@/Components/PageBuilder/EditorMenu/Editables/ImageEditor.vue";
import ColorsEditor from "@/Components/PageBuilder/EditorMenu/Editables/ColorEditor.vue";
import Typography from "@/Components/PageBuilder/EditorMenu/Editables/Typography.vue";
import PaddingPlusMargin from "@/Components/PageBuilder/EditorMenu/Editables/PaddingPlusMargin.vue";
import DeleteElement from "@/Components/PageBuilder/EditorMenu/Editables/DeleteElement.vue";
import BorderRadius from "@/Components/PageBuilder/EditorMenu/Editables/BorderRadius.vue";
import Borders from "@/Components/PageBuilder/EditorMenu/Editables/Borders.vue";
import LinkEditor from "@/Components/PageBuilder/EditorMenu/Editables/LinkEditor.vue";
import ComponentTopMenu from "@/Components/PageBuilder/EditorMenu/Editables/ComponentTopMenu.vue";
import TipTap from "@/Components/TipTap/TipTap.vue";

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

const reactiveGetElement = ref(null);

// get current element tag
const getComponent = computed(() => {
    return store.getters["pageBuilderState/getComponent"];
});
const getElement = computed(() => {
    return store.getters["pageBuilderState/getElement"];
});

const getElementOuterHTML = computed(() => {
    if (getElement.value === null) return;
    return getElement.value.outerHTML ? getElement.value.outerHTML : null;
});

watch(getElementOuterHTML, (newComponent) => {
    reactiveGetElement.value;
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
const getRestoredElement = computed(() => {
    return store.getters["pageBuilderState/getRestoredElement"];
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
                <button
                    type="button"
                    @click="$emit('closeEditor')"
                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                >
                    <XMarkIcon
                        class="shrink-0 h-4 w-4 m-2 stroke-2"
                    ></XMarkIcon>
                </button>
                <p class="font-bold text-sm">
                    Editing
                    <span class="lowercase">&lt;{{ elementTag }}&gt;</span>
                </p>
            </div>

            <div class="mb-4 overflow-y-scroll md:pb-24 pb-12">
                <p class="my-12 pl-4">
                    getElement: {{ JSON.stringify(getElement) }}
                </p>
                <p class="my-12 pl-4">
                    reactiveGetElement: {{ JSON.stringify(reactiveGetElement) }}
                </p>
                <template v-if="getElement">
                    <div v-show="isHeadingElement === true">
                        <article>
                            <ComponentTopMenu></ComponentTopMenu>
                        </article>
                        <article>
                            <ImageEditor :user="user" :team="team">
                            </ImageEditor>
                        </article>
                        <article>
                            <TipTap></TipTap>
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
                </template>
            </div>
        </div>
    </div>
</template>
